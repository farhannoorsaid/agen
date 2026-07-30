<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockIn;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockInController extends Controller
{
    /**
     * Display a listing of stock in.
     */
    public function index()
    {
        $stockIns = StockIn::with(['barang.suppliers' => function($q) {
            $q->withTrashed();
        }, 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('stok-masuk.index', compact('stockIns'));
    }

    /**
     * Show the form for creating a new stock in.
     */
    public function create()
    {
        // Hanya tampil barang yang aktif (tidak diarsip)
        // Load supplier dengan withTrashed() untuk barang dari supplier yang dihapus
        $barangs = Barang::active()->with(['suppliers' => function($q) {
            $q->withTrashed();
        }])->get();
        $suppliers = Supplier::all();
        return view('stok-masuk.create', compact('barangs', 'suppliers'));
    }

    /**
     * Store a newly created stock in.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.supplier_id' => 'required|exists:suppliers,id',
            'items.*.jumlah_masuk' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string|max:500',
            'items.*.tanggal_kedaluwarsa' => 'required|date',
            'items.*.nomor_lot' => 'nullable|string|max:100',
        ]);

        $invoiceNumber = 'IN-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        try {
            DB::transaction(function () use ($validated, $invoiceNumber) {
                foreach ($validated['items'] as $item) {
                    $barang = Barang::findOrFail($item['barang_id']);
                    
                    // Tambah stok barang
                    $barang->increment('stok', $item['jumlah_masuk']);

                    // Simpan riwayat stock in
                    StockIn::create([
                        'invoice_number' => $invoiceNumber,
                        'barang_id' => $item['barang_id'],
                        'supplier_id' => $item['supplier_id'],
                        'jumlah_masuk' => $item['jumlah_masuk'],
                        'sisa' => $item['jumlah_masuk'],
                        'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'],
                        'nomor_lot' => $item['nomor_lot'] ?? null,
                        'keterangan' => $item['keterangan'] ?? null,
                        'user_id' => Auth::id(),
                    ]);
                }
            });

            return redirect()->route('stok-masuk.index')->with('success', 'Transaksi Pembelian berhasil disimpan (No. Nota: ' . $invoiceNumber . ')');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the details of stock in (read-only).
     */
    public function show($id)
    {
        $stockIn = StockIn::with('barang.suppliers', 'user')->findOrFail($id);
        return view('stok-masuk.show', compact('stockIn'));
    }

    /**
     * Show the form for editing the stock in (read-only for qty, editable for meta info).
     */
    public function edit($id)
    {
        $stockIn = StockIn::with('barang')->findOrFail($id);
        return view('stok-masuk.edit', compact('stockIn'));
    }

    /**
     * Update meta information of stock in.
     */
    public function update(Request $request, $id)
    {
        $stockIn = StockIn::findOrFail($id);

        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:500',
            'tanggal_kedaluwarsa' => 'required|date',
            'nomor_lot' => 'nullable|string|max:100',
        ]);

        $stockIn->update($validated);

        return redirect()->route('stok-masuk.index')->with('success', 'Informasi stok masuk berhasil diperbarui');
    }

    /**
     * Delete stock in (undo transaksi).
     * Note: Ini untuk undo transaksi jika ada kesalahan
     */
    public function destroy($id)
    {
        $stockIn = StockIn::findOrFail($id);
        $barang = $stockIn->barang;

        // Kurangi stok barang kembali
        $barang->decrement('stok', $stockIn->jumlah_masuk);

        // Hapus record stock in
        $stockIn->delete();

        return redirect()->route('stok-masuk.index')->with('success', 'Transaksi stok masuk berhasil dibatalkan');
    }
}