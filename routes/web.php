<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ✅ Data Barang
    Route::resource('/barang', BarangController::class);
   
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');

    // ✅ Pemasok/Supplier
    Route::resource('/pemasok', SupplierController::class);

    // ✅ Stok Masuk
    Route::resource('/stok-masuk', StockInController::class);

    // ✅ Stok Keluar (Penjualan)
    Route::resource('/stok-keluar', StockOutController::class);

    // ✅ Laporan
    Route::prefix('/laporan')->group(function () {
        Route::get('/stok', [ReportController::class, 'stok'])->name('laporan.stok');
        Route::get('/stok/export/excel', [ReportController::class, 'exportStokExcel'])->name('laporan.stok.export-excel');
        Route::get('/stok/export/pdf', [ReportController::class, 'exportStokPdf'])->name('laporan.stok.export-pdf');
        
        Route::get('/penjualan', [ReportController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/penjualan/export/excel', [ReportController::class, 'exportPenjualanExcel'])->name('laporan.penjualan.export-excel');
        Route::get('/penjualan/export/pdf', [ReportController::class, 'exportPenjualanPdf'])->name('laporan.penjualan.export-pdf');
        
        Route::get('/detail-stok/{barangId}', [ReportController::class, 'detailStok'])->name('laporan.detail-stok');
    });

});

require __DIR__.'/auth.php';
use App\Http\Controllers\ApiController;

Route::middleware(['auth'])->group(function () {
    // API Routes untuk AJAX
    Route::prefix('/api')->group(function () {
        Route::get('/barang/{id}', [ApiController::class, 'getBarang']);
    });
});
