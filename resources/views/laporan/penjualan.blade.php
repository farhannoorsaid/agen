@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="money" class="inline h-6 w-6 mr-3 text-white"/> Laporan Penjualan</h1>
            <p class="text-orange-100 mt-2">Data penjualan dengan snapshot harga dan supplier</p>
        </div>

        <!-- Filter Form - Compact Horizontal -->
        <div class="bg-orange-50 rounded-lg shadow-md p-4 mb-8 border border-orange-200">
            <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="search" class="inline h-4 w-4 mr-2"/> Filter
                </button>
                <a href="{{ route('laporan.penjualan') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    ↻ Reset
                </a>
                <a href="{{ route('laporan.penjualan.export-excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="download" class="inline h-4 w-4 mr-2"/> Excel
                </a>
                <a href="{{ route('laporan.penjualan.export-pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="download" class="inline h-4 w-4 mr-2"/> PDF
                </a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Transaksi</h3>
                <p class="text-3xl font-bold text-blue-600 mt-3">{{ $stockOuts->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Qty</h3>
                <p class="text-3xl font-bold text-green-600 mt-3">{{ $totalQty }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Pendapatan</h3>
                <p class="text-2xl font-bold text-purple-600 mt-3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Belum Lunas</h3>
                <p class="text-2xl font-bold text-red-600 mt-3">Rp {{ number_format($totalBelum, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Barang (Snapshot)</th>

                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Qty</th>
                        <th class="px-6 py-4 text-right text-gray-700 font-semibold">Harga (Snapshot)</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOuts as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 text-sm">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->product_name_snapshot ?? $item->barang->nama_barang ?? '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold">{{ $item->jumlah_terjual }} {{ $item->barang->satuan ?? '' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">Rp {{ number_format($item->price_snapshot ?? $item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $item->status_pembayaran === 'lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    @if($item->status_pembayaran === 'lunas')
                                        <x-icon name="check" class="inline h-4 w-4 mr-1 text-green-800"/> Lunas
                                    @else
                                        <x-icon name="hourglass" class="inline h-4 w-4 mr-1 text-red-800"/> Belum Lunas
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                Belum ada data penjualan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 mb-8">
            {{ $stockOuts->links() }}
        </div>

        <!-- Footer Info -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
            <h3 class="font-semibold text-blue-900 mb-2">ℹ️ Catatan Penting</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• <strong>Snapshot Data:</strong> Nama barang dan supplier yang ditampilkan adalah data pada saat transaksi dilakukan</li>
                <li>• <strong>Harga Snapshot:</strong> Harga yang ditampilkan adalah harga pada saat penjualan, meskipun master data berubah</li>
                <li>• <strong>Keandalan Data:</strong> Data laporan tidak akan berubah meski master data diedit kemudian</li>
            </ul>
        </div>
    </div>
</div>
@endsection
