<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use League\Csv\Writer;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Helper: terapkan filter supplier & status ke query Barang
     */
    protected function applyBarangFilters($query, Request $request)
    {
        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->whereHas('suppliers', function($q) use ($request) {
                $q->where('suppliers.id', $request->supplier_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->whereRaw('stok <= stok_minimum');
            } elseif ($request->status === 'normal') {
                $query->whereRaw('stok > stok_minimum');
            } elseif ($request->status === 'expiring') {
                $query->whereHas('stockIns', function($q) {
                    $q->where('sisa', '>', 0)
                      ->whereNotNull('tanggal_kedaluwarsa')
                      ->whereRaw('DATEDIFF(tanggal_kedaluwarsa, NOW()) <= 30');
                });
            }
        }

        return $query;
    }

    /**
     * Laporan Stok - Real-time inventory status
     */
    public function stok(Request $request): View
    {
        // $query = Barang::active()->with('suppliers');

        $firstExpiredDate = DB::table('stock_ins')
            ->select([
                'barang_id',
                DB::raw('MIN(tanggal_kedaluwarsa) AS first_exp_date'),
            ])
            ->whereNotNull('tanggal_kedaluwarsa')
            ->where('jumlah_masuk', '>', 0)
            ->groupBy('barang_id');

        $query = Barang::active()
            ->where('barangs.row_status', 1)    
            ->with('suppliers')
            ->leftJoinSub(
                $firstExpiredDate,
                'first_expired_stock',
                function ($join) {
                    $join->on(
                        'barangs.id',
                        '=',
                        'first_expired_stock.barang_id'
                    );
                }
            )
            ->select([
                'barangs.*',
                'first_expired_stock.first_exp_date',
            ]);
        
        $query = $this->applyBarangFilters($query, $request);

        $totalQuery = clone $query;
        $allBarang = $totalQuery->get();

        $totalBarang = $allBarang->count();
        $totalStok = $allBarang->sum('stok');
        $totalLow = $allBarang->filter(function($item) {
            return $item->stok <= $item->stok_minimum;
        })->count();

        $barang = $query->paginate(15)->withQueryString();
        $suppliers = Supplier::all();

        return view('laporan.stok', [
            'barang' => $barang,
            'totalBarang' => $totalBarang,
            'totalStok' => $totalStok,
            'totalLow' => $totalLow,
            'suppliers' => $suppliers,
            'filters' => $request->only(['supplier_id', 'status']),
        ]);
    }

    /**
     * Export Laporan Stok to Excel
     */
    public function exportStokExcel(Request $request)
    {
        // $query = Barang::active()->with('suppliers');
        $firstExpiredDate = DB::table('stock_ins')
        ->select([
            'barang_id',
            DB::raw('MIN(tanggal_kedaluwarsa) AS first_exp_date'),
        ])
        ->where('jumlah_masuk', '>', 0)
        ->whereNotNull('tanggal_kedaluwarsa')
        ->groupBy('barang_id');

        $query = Barang::active()
            ->with('suppliers')
            ->leftJoinSub(
                $firstExpiredDate,
                'first_expired_stock',
                function ($join) {
                    $join->on(
                        'barangs.id',
                        '=',
                        'first_expired_stock.barang_id'
                    );
                }
            )
            ->select([
                'barangs.*',
                'first_expired_stock.first_exp_date',
            ]);

        $query = $this->applyBarangFilters($query, $request);

        $barang = $query->get();

        // Buat file CSV
        $csv = Writer::createFromPath(tempnam(sys_get_temp_dir(), 'stok_'), 'w+');

        // Tambahkan header
        $csv->insertOne(['Nama Barang', 'Stok', 'Min', 'Harga Jual', 'Status', 'Exp. Date']);

        // Tambahkan data
        foreach ($barang as $item) {
            $status = $item->stok <= $item->stok_minimum ? 'Stok Rendah' : 'Normal';

            // $firstBatch = $item->stockIns()->where('sisa', '>', 0)->orderBy('created_at')->first();
            // $expDate = $firstBatch && $firstBatch->tanggal_kedaluwarsa
            //     ? $firstBatch->tanggal_kedaluwarsa->format('d-m-Y')
            //     : '-';

            $expDate = $item->first_exp_date
            ? Carbon::parse($item->first_exp_date)->format('d-m-Y')
            : '-';

            $csv->insertOne([
                $item->nama_barang,
                $item->stok,
                $item->stok_minimum,
                $item->harga_jual,
                $status,
                $expDate,
            ]);
        }

        return response($csv->toString(), Response::HTTP_OK, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan-Stok-' . now()->format('Y-m-d-His') . '.csv"',
        ]);
    }

    /**
     * Export Laporan Stok to PDF
     */
    public function exportStokPdf(Request $request)
    {
        $query = Barang::active()->with('suppliers');
        $query = $this->applyBarangFilters($query, $request);

        $barang = $query->get();

        // Render HTML untuk PDF
        $html = view('laporan.stok-pdf', ['barang' => $barang])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Laporan-Stok-' . now()->format('Y-m-d-His') . '.pdf"',
        ]);
    }

    /**
     * Laporan Penjualan - Sales report dengan snapshot data
     */
    public function penjualan(Request $request): View
    {
        $query = StockOut::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }
        $stockOuts = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $totalQuery = clone $query;
        $totalStockOuts = $totalQuery->get();

        $totalQty = $totalStockOuts->sum('jumlah_terjual');
        $totalRevenue = $totalStockOuts->sum('total_harga');
        $totalLunas = $totalStockOuts->where('status_pembayaran', 'lunas')->sum('total_harga');
        $totalBelum = $totalStockOuts->where('status_pembayaran', 'belum_lunas')->sum('total_harga');

        return view('laporan.penjualan', [
            'stockOuts' => $stockOuts,
            'totalQty' => $totalQty,
            'totalRevenue' => $totalRevenue,
            'totalLunas' => $totalLunas,
            'totalBelum' => $totalBelum,
            'filters' => $request->only(['tanggal_dari', 'tanggal_sampai', 'status']),
        ]);
    }

    /**
     * Export Laporan Penjualan to Excel
     */
    public function exportPenjualanExcel(Request $request)
    {
        $query = StockOut::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $stockOuts = $query->orderBy('created_at', 'desc')->get();

        $csv = Writer::createFromPath(tempnam(sys_get_temp_dir(), 'penjualan_'), 'w+');
        $csv->insertOne(['Tanggal', 'Produk', 'Supplier', 'Qty', 'Harga/Unit', 'Total', 'Status Pembayaran']);

        foreach ($stockOuts as $item) {
            $csv->insertOne([
                $item->created_at->format('d M Y H:i'),
                $item->product_name_snapshot,
                $item->supplier_name_snapshot,
                $item->jumlah_terjual,
                $item->price_snapshot / $item->jumlah_terjual,
                $item->total_harga,
                ucfirst(str_replace('_', ' ', $item->status_pembayaran)),
            ]);
        }

        return response($csv->toString(), Response::HTTP_OK, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan-Penjualan-' . now()->format('Y-m-d-Hi') . '.csv"',
        ]);
    }

    /**
     * Export Laporan Penjualan to PDF
     */
    public function exportPenjualanPdf(Request $request)
    {
        $query = StockOut::query();

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        $stockOuts = $query->orderBy('created_at', 'desc')->get();

        $totalQty = $stockOuts->sum('jumlah_terjual');
        $totalRevenue = $stockOuts->sum('total_harga');
        $totalLunas = $stockOuts->where('status_pembayaran', 'lunas')->sum('total_harga');
        $totalBelum = $stockOuts->where('status_pembayaran', 'belum_lunas')->sum('total_harga');

        $html = view('laporan.penjualan-pdf', [
            'stockOuts' => $stockOuts,
            'totalQty' => $totalQty,
            'totalRevenue' => $totalRevenue,
            'totalLunas' => $totalLunas,
            'totalBelum' => $totalBelum,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Laporan-Penjualan-' . now()->format('Y-m-d-Hi') . '.pdf"',
        ]);
    }

    /**
     * Detail Stok breakdown
     */
    public function detailStok($barangId): View
    {
        $barang = Barang::active()->findOrFail($barangId);

        $stockIns = StockIn::where('barang_id', $barangId)->orderBy('created_at', 'desc')->get();
        $stockOuts = StockOut::where('barang_id', $barangId)->orderBy('created_at', 'desc')->get();

        return view('laporan.detail-stok', [
            'barang' => $barang,
            'stockIns' => $stockIns,
            'stockOuts' => $stockOuts,
        ]);
    }
}