<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockOut;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Data untuk dashboard
        $barangLowStock = Barang::active()->lowStock()->get();
        $barangExpiringSoon = Barang::active()->expiringSoon()->get();

        // Total data untuk overview
        $totalBarang = Barang::active()->count();
        $totalStok = Barang::active()->sum('stok');
        $totalPenjualan = StockOut::sum('total_harga');

        // Carousel images - update dengan path ke folder public/images/carousel/
        $carouselImages = [
            asset('images/carousel/banner1.jpg'),
            asset('images/carousel/banner2.jpg'),
            asset('images/carousel/banner3.jpg'),
        ];

        return view('dashboard', [
            'barangLowStock' => $barangLowStock,
            'barangExpiringSoon' => $barangExpiringSoon,
            'totalBarang' => $totalBarang,
            'totalStok' => $totalStok,
            'totalPenjualan' => $totalPenjualan,
            'carouselImages' => $carouselImages,
        ]);
    }
}
