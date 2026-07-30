@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="download" class="inline h-6 w-6 mr-2 text-white"/> Transaksi Pembelian</h1>
            <p class="text-blue-100 mt-2">Catat barang masuk dari supplier dengan tracking batch dan exp.date</p>
        </div>

        @if ($message = Session::get('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm inline-flex items-center gap-2">
                <x-icon name="check" class="inline h-5 w-5 text-green-700"/> {{ $message }}
            </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <a href="{{ route('stok-masuk.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-md inline-flex items-center">
                <x-icon name="plus" class="inline h-4 w-4 mr-2"/> Input Transaksi Pembelian
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">No. Transaksi</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Barang</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Supplier</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Qty Masuk</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Tgl Kadaluwarsa</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Keterangan</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Input Oleh</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockIns as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-700 font-mono text-xs">{{ $item->invoice_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->barang->nama_barang }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                <div class="flex items-center gap-2">
                                    {{ $item->supplier?->nama_supplier ?? ($item->barang->suppliers->count() > 0 ? $item->barang->suppliers->pluck('nama_supplier')->join(', ') : '-') }}
                                    @if($item->supplier && $item->supplier->deleted_at)
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                                            <x-icon name="archive" class="inline h-4 w-4 mr-1 text-orange-700"/> Diarsipkan
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-blue-200 text-blue-800">
                                    {{ $item->jumlah_masuk }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                @if($item->tanggal_kedaluwarsa)
                                    <span class="font-semibold text-orange-700">{{ $item->tanggal_kedaluwarsa->format('d M Y') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700 text-xs">{{ $item->keterangan ? Str::limit($item->keterangan, 30) : '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('stok-masuk.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition shadow-sm inline-flex items-center">
                                    <x-icon name="edit" class="inline h-4 w-4 mr-1"/> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                Belum ada data transaksi pembelian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $stockIns->links() }}
        </div>
    </div>
</div>
@endsection
