@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="chart" class="inline h-6 w-6 mr-3 text-white"/> Laporan Stok</h1>
            <p class="text-purple-100 mt-2">Status inventori real-time semua barang</p>
        </div>

        <!-- Filter Form - Compact Horizontal -->
        <div class="bg-purple-50 rounded-lg shadow p-4 mb-8 border border-purple-200">
            <form method="GET" action="{{ route('laporan.stok') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Supplier</label>
                    <select name="supplier_id" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Semua Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[140px]">
                    <label class="text-xs font-semibold text-gray-700 block mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">-- Semua Status --</option>
                        <option value="normal" {{ request('status') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="low" {{ request('status') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Hampir Kadaluarsa</option>
                    </select>
                </div>
                <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="search" class="inline h-4 w-4 mr-2"/> Filter
                </button>
                <a href="{{ route('laporan.stok') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    ↻ Reset
                </a>
                <a href="{{ route('laporan.stok.export-excel', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="download" class="inline h-4 w-4 mr-2"/> Excel
                </a>
                <a href="{{ route('laporan.stok.export-pdf', request()->query()) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-1.5 text-sm rounded transition shadow-sm">
                    <x-icon name="download" class="inline h-4 w-4 mr-2"/> PDF
                </a>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Barang</h3>
                <p class="text-4xl font-bold text-blue-600 mt-3">{{ $totalBarang }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Stok</h3>
                <p class="text-4xl font-bold text-green-600 mt-3">{{ $totalStok }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Barang Stok Rendah</h3>
                <p class="text-4xl font-bold text-red-600 mt-3">{{ $totalLow }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Barang</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Stok Saat Ini</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Stok Minimum</th>
                        <th class="px-6 py-4 text-right text-gray-700 font-semibold">Harga Jual</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Exp.Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                        @continue(!$item->first_exp_date)
                        <tr class="border-b hover:bg-gray-50 transition {{ $item->stok <= $item->stok_minimum ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $item->stok <= $item->stok_minimum ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                    {{ $item->stok }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">{{ $item->stok_minimum }}</td>
                            <td class="px-6 py-4 text-right text-gray-800 font-semibold">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->stok <= $item->stok_minimum)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-200 text-red-800">🔴 Stok Rendah</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-200 text-green-800"><x-icon name="check" class="inline h-4 w-4 mr-1 text-green-800"/> Normal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <span class="text-sm font-semibold text-orange-700">{{ \Carbon\Carbon::parse($item->first_exp_date)->format('d-m-Y') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $barang->links() }}
        </div>
    </div>
</div>
@endsection
