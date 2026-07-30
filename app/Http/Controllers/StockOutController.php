<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockOutController extends Controller
{
    /**
     * Display a listing of stock out (penjualan).
     */
    public function index()
    {
        $query = StockOut::select(
            'invoice_number',
            DB::raw('MAX(created_at) as created_at'),
            DB::raw('SUM(total_harga) as grand_total'),
            DB::raw('MAX(user_id) as user_id')
        )
        ->whereNotNull('invoice_number')
        ->with('user')
        ->groupBy('invoice_number')
        ->orderByDesc('created_at');

        $totalAll = StockOut::whereNotNull('invoice_number')->sum('total_harga');

        $stockOuts = $query->paginate(15);
        
        return view('stok-keluar.index', compact('stockOuts', 'totalAll'));
    }

    /**
     * Show the form for creating a new stock out (penjualan).
     */
    public function create()
    {
        // Hanya tampil barang yang aktif (tidak diarsip) dan punya stok
        // Load supplier dengan withTrashed() untuk barang dari supplier yang dihapus
        $barangs = Barang::active()
            ->where('stok', '>', 0)
            ->with(['suppliers' => function($q) {
                $q->withTrashed();
            }])
            ->get();
        
        return view('stok-keluar.create', compact('barangs'));
    }

    /**
     * Store a newly created stock out (penjualan).
     * ⚠️ SANGAT PENTING: Menyimpan SNAPSHOT data saat transaksi terjadi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah_terjual' => 'required|integer|min:1',
        ]);

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        try {
            DB::transaction(function () use ($validated, $invoiceNumber) {
                foreach ($validated['items'] as $item) {
                    $barang = Barang::with(['suppliers' => function($q) {
                        $q->withTrashed();
                    }])->findOrFail($item['barang_id']);

                    if ($barang->stok < $item['jumlah_terjual']) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak cukup. Stok tersedia: " . $barang->stok);
                    }

                    $supplierName = $barang->suppliers->count() > 0 ? $barang->suppliers->pluck('nama_supplier')->join(', ') : 'Supplier Tidak Ditemukan';
                    $totalHarga = $barang->harga_jual * $item['jumlah_terjual'];

                    StockOut::create([
                        'invoice_number' => $invoiceNumber,
                        'barang_id' => $item['barang_id'],
                        'jumlah_terjual' => $item['jumlah_terjual'],
                        'product_name_snapshot' => $barang->nama_barang,
                        'supplier_name_snapshot' => $supplierName,
                        'price_snapshot' => $totalHarga,
                        'total_harga' => $totalHarga,
                        'status_pembayaran' => 'lunas',
                        'user_id' => Auth::id(),
                    ]);

                    // FEFO Alokasi (First-Expired, First-Out)
                    $need = $item['jumlah_terjual'];
                    $stockIns = StockIn::where('barang_id', $barang->id)
                        ->where('sisa', '>', 0)
                        ->orderByRaw('ISNULL(tanggal_kedaluwarsa), tanggal_kedaluwarsa ASC, created_at ASC')
                        ->get();

                    foreach ($stockIns as $si) {
                        if ($need <= 0) break;
                        $take = min($si->sisa, $need);
                        $si->decrement('sisa', $take);
                        $need -= $take;
                    }

                    $barang->decrement('stok', $item['jumlah_terjual']);
                }
            });

            return redirect()->route('stok-keluar.index')->with('success', 'Penjualan berhasil dicatat (No. Nota: ' . $invoiceNumber . ')');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the stock out.
     */
    public function edit($id)
    {
        $stockOuts = StockOut::where('invoice_number', $id)->get();
        if ($stockOuts->isEmpty()) {
            abort(404);
        }
        $invoiceNumber = $id;
        $statusPembayaran = $stockOuts->first()->status_pembayaran;
        $grandTotal = $stockOuts->sum('total_harga');

        return view('stok-keluar.edit', compact('stockOuts', 'invoiceNumber', 'statusPembayaran', 'grandTotal'));
    }

    /**
     * Update pembayaran status dari belum_lunas menjadi lunas atau sebaliknya
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:lunas,belum_lunas',
        ]);

        $stockOuts = StockOut::where('invoice_number', $id)->get();
        if ($stockOuts->isEmpty()) {
            abort(404);
        }

        foreach ($stockOuts as $stockOut) {
            $stockOut->update($validated);
        }

        return redirect()->route('stok-keluar.index')->with('success', 'Status pembayaran berhasil diperbarui');
    }

    /**
     * Show detail penjualan
     */
    public function show($id)
    {
        // $id di sini bertindak sebagai invoice_number
        $stockOuts = StockOut::with('barang', 'user')->where('invoice_number', $id)->get();
        if ($stockOuts->isEmpty()) {
            abort(404);
        }
        
        $invoiceNumber = $id;
        $tanggal = $stockOuts->first()->created_at;
        $kasir = $stockOuts->first()->user->name;
        $grandTotal = $stockOuts->sum('total_harga');

        return view('stok-keluar.show', compact('stockOuts', 'invoiceNumber', 'tanggal', 'kasir', 'grandTotal'));
    }

    /**
     * Delete stock out (undo penjualan jika ada kesalahan)
     */
    public function destroy($id)
    {
        // Delete by invoice_number instead of id
        $stockOuts = StockOut::where('invoice_number', $id)->get();
        if ($stockOuts->isEmpty()) {
            return redirect()->route('stok-keluar.index')->with('error', 'Nota tidak ditemukan');
        }

        DB::transaction(function () use ($stockOuts) {
            foreach ($stockOuts as $stockOut) {
                $barang = $stockOut->barang;
                if ($barang) {
                    $barang->increment('stok', $stockOut->jumlah_terjual);
                }
                $stockOut->delete();
            }
        });

        return redirect()->route('stok-keluar.index')->with('success', 'Transaksi penjualan berhasil dibatalkan (restock otomatis)');
    }
}
