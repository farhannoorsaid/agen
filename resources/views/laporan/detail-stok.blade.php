@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold">Detail Stok: {{ $barang->nama_barang }}</h1>
            <p class="text-blue-100 mt-2">Supplier: {{ $barang->supplier->nama_supplier }}</p>
        </div>

        <!-- Info Box -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-semibold uppercase">Stok Terkini</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $barang->stok }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-semibold uppercase">Total Masuk</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stockIns->sum('jumlah_masuk') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-semibold uppercase">Total Keluar</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stockOuts->sum('jumlah_keluar') }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-semibold uppercase">Harga Jual</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button class="tab-button px-6 py-4 font-semibold text-gray-700 border-b-2 border-blue-600 tab-in" onclick="switchTab('in')">
                        <x-icon name="download" class="inline h-5 w-5 mr-2 text-amber-600"/> Transaksi Pembelian ({{ count($stockIns) }})
                    </button>
                    <button class="tab-button px-6 py-4 font-semibold text-gray-700 hover:text-gray-900" onclick="switchTab('out')">
                        📤 Transaksi Penjualan ({{ count($stockOuts) }})
                    </button>
                </div>
            </div>

            <!-- Transaksi Pembelian Tab -->
            <div id="tab-in" class="tab-content p-6">
                @if($stockIns->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada data transaksi pembelian</p>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Jumlah</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Keterangan</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Input Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockIns as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-700">{{ $item->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-green-600">+{{ $item->jumlah_masuk }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $item->user->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Transaksi Penjualan Tab -->
            <div id="tab-out" class="tab-content p-6 hidden">
                @if($stockOuts->isEmpty())
                    <p class="text-gray-500 text-center py-8">Belum ada data transaksi penjualan</p>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-center text-gray-700 font-semibold">Jumlah</th>
                                <th class="px-6 py-4 text-right text-gray-700 font-semibold">Harga/Unit</th>
                                <th class="px-6 py-4 text-right text-gray-700 font-semibold">Total</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Status</th>
                                <th class="px-6 py-4 text-left text-gray-700 font-semibold">Input Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockOuts as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-700">{{ $item->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-center font-bold text-orange-600">-{{ $item->jumlah_keluar }}</td>
                                    <td class="px-6 py-4 text-right">Rp {{ number_format($item->price_snapshot / $item->jumlah_keluar, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-800">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $item->status_pembayaran === 'lunas' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                            @if($item->status_pembayaran === 'lunas')
                                                <x-icon name="check" class="inline h-4 w-4 mr-1 text-green-800"/> Lunas
                                            @else
                                                <x-icon name="hourglass" class="inline h-4 w-4 mr-1 text-red-800"/> Belum Lunas
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">{{ $item->user->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <a href="{{ route('laporan.stok') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition inline-block">
            ← Kembali ke Laporan Stok
        </a>
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-b-2', 'border-blue-600', 'tab-in');
    });

    // Show selected tab
    document.getElementById(`tab-${tab}`).classList.remove('hidden');
    event.target.classList.add('border-b-2', 'border-blue-600', `tab-${tab}`);
}
</script>
@endsection
