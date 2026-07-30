@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-4xl font-bold mb-2">Dashboard Manajemen Stok</h1>
            <p class="text-blue-100">Selamat datang di sistem manajemen stok Agen Hendi</p>
        </div>

        <!-- Alert Success -->
        <x-alert-success />

        <!-- Info Banner -->
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg mb-8">
            <p class="text-lg font-semibold">
                <x-icon name="check" class="inline h-4 w-4 mr-2 text-green-600"/> Pastikan stok selalu terjaga untuk kelancaran operasional
            </p>
        </div>

        <!-- Image Carousel/Banner Slider -->
        <div class="mb-8">
            <div id="imageCarousel" class="relative w-full rounded-lg shadow-lg overflow-hidden bg-gray-200">
                <!-- Carousel Container - Aspect Ratio 4:1 untuk landscape image -->
                <div class="relative w-full" style="aspect-ratio: 4 / 1;">
                    <!-- Image Display -->
                    <img 
                        id="carouselImage" 
                        src="https://via.placeholder.com/1200x400?text=Agen+Hendi+Banner" 
                        alt="Carousel Banner"
                        class="w-full h-full object-cover transition-opacity duration-500 hover:opacity-90"
                    >
                </div>

                <!-- Navigation Buttons -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                    <button onclick="rotateCarousel(-1)" class="bg-white/70 hover:bg-white text-gray-800 font-bold py-2 px-4 rounded transition shadow-md">
                        ← Previous
                    </button>
                    <button onclick="rotateCarousel(1)" class="bg-white/70 hover:bg-white text-gray-800 font-bold py-2 px-4 rounded transition shadow-md">
                        Next →
                    </button>
                </div>
            </div>

            <!-- Info Text removed as requested -->
        </div>

        <!-- Warnings Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Stok Rendah -->
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
                <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                     Stok Rendah ({{ $barangLowStock->count() }})
                </h3>
                
                @if($barangLowStock->isEmpty())
                    <p class="text-red-700">Semua barang memiliki stok yang cukup <x-icon name="check" class="inline h-4 w-4 ml-1 text-green-600"/></p>
                @else
                    <div class="space-y-3 max-h-[350px] overflow-y-auto pr-2">
                        @foreach($barangLowStock as $barang)
                            <div class="bg-white p-3 rounded border border-red-200">
                                <p class="font-semibold text-gray-800">{{ $barang->nama_barang }}</p>
                                <div class="flex justify-between text-sm text-gray-600 mt-1">
                                    <span>Stok: <span class="font-bold text-red-600">{{ $barang->stok }}</span></span>
                                    <span>Min: {{ $barang->stok_minimum }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Barang Hampir Kadaluarsa -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6">
                <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                    </svg>
                     Barang Hampir Kadaluarsa (< 30 hari) ({{ $barangExpiringSoon->count() }})
                </h3>
                
                @if($barangExpiringSoon->isEmpty())
                    <p class="text-yellow-700">Tidak ada barang yang akan kadaluarsa dalam 30 hari ke depan <x-icon name="check" class="inline h-4 w-4 ml-1 text-yellow-700"/></p>
                @else
                    <div class="space-y-3 max-h-[350px] overflow-y-auto pr-2">
                        @foreach($barangExpiringSoon as $barang)
                            @php
                                $firstBatch = $barang->stockIns()->where('sisa', '>', 0)->whereNotNull('tanggal_kedaluwarsa')->orderBy('tanggal_kedaluwarsa', 'asc')->first();
                                $expDate = $firstBatch?->tanggal_kedaluwarsa;
                            @endphp
                            @if($expDate)
                            <div class="bg-white p-3 rounded border border-yellow-200">
                                <p class="font-semibold text-gray-800">{{ $barang->nama_barang }}</p>
                                <div class="flex justify-between text-sm text-gray-600 mt-1">
                                    <span>Kadaluarsa: <span class="font-bold text-yellow-600">{{ $expDate->format('d M Y') }}</span></span>
                                    <span class="text-yellow-600">{{ $expDate->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><x-icon name="bolt" class="inline h-5 w-5 mr-2 text-amber-500"/> Akses Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('stok-masuk.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded text-center transition">
                    Stok Masuk
                </a>
                <a href="{{ route('stok-keluar.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded text-center transition">
                    Penjualan
                </a>
                <a href="{{ route('laporan.stok') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-4 rounded text-center transition">
                    Laporan Stok
                </a>
                <a href="{{ route('laporan.penjualan') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded text-center transition">
                    Laporan Penjualan
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Carousel Images Array - from controller
const carouselImages = {!! json_encode($carouselImages) !!};

let currentImageIndex = 0;

function rotateCarousel(direction) {
    currentImageIndex += direction;
    
    // Wrap around
    if (currentImageIndex >= carouselImages.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = carouselImages.length - 1;
    }
    
    // Update image with fade effect
    const img = document.getElementById('carouselImage');
    img.style.opacity = '0.5';
    
    setTimeout(() => {
        img.src = carouselImages[currentImageIndex];
        img.style.opacity = '1';
    }, 250);
}

// Auto-rotate carousel every 5 seconds
setInterval(() => {
    rotateCarousel(1);
}, 5000);
</script>

<style>
#carouselImage {
    opacity: 1;
    transition: opacity 0.3s ease-in-out;
}
</style>
@endsection