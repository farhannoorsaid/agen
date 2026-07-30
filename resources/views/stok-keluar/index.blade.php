@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="cart" class="inline h-6 w-6 mr-2 text-white"/> Transaksi Penjualan</h1>
            <p class="text-green-100 mt-2">Kelola transaksi penjualan dengan FIFO otomatis</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm inline-flex items-center gap-2">
                <x-icon name="check" class="inline h-5 w-5 text-green-700"/> {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm inline-flex items-center gap-2">
                <x-icon name="x" class="inline h-5 w-5 text-red-700"/> {{ $message }}
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <a href="{{ route('stok-keluar.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-md inline-flex items-center">
                <x-icon name="plus" class="inline h-4 w-4 mr-2"/> Input Penjualan Kasir
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">No. Nota</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Kasir</th>
                        <th class="px-6 py-4 text-right text-gray-700 font-semibold">Total Transaksi</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockOuts as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->invoice_number }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('stok-keluar.show', $item->invoice_number) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition shadow-sm flex items-center gap-1">
                                        <x-icon name="document" class="inline h-4 w-4" />
                                        <span>Detail Nota</span>
                                    </a>
                                    {{-- <a href="{{ route('stok-keluar.edit', $item->invoice_number) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition shadow-sm flex items-center gap-1">
                                        <x-icon name="edit" class="inline h-4 w-4" />
                                        <span>Edit</span>
                                    </a> --}}
                                </div>
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

        <div class="mt-4">
            {{ $stockOuts->links() }}
        </div>

        <!-- Summary Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-1 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <h3 class="text-gray-600 text-xs font-semibold uppercase tracking-wider">Total Semua Penjualan</h3>
                <p class="text-3xl font-bold text-purple-600 mt-3">
                    Rp {{ number_format($totalAll, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
