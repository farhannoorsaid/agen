@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="edit" class="inline h-6 w-6 mr-2 text-white"/> Edit Transaksi Pembelian</h1>
            <p class="text-blue-100 mt-2">Ubah informasi tambahan untuk batch stok ini (Jumlah masuk tidak bisa diubah)</p>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <form action="{{ route('stok-masuk.update', $stockIn->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <!-- Readonly Info -->
                <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2"><strong>Barang:</strong> {{ $stockIn->barang->nama_barang }}</p>
                    <p class="text-sm text-gray-700"><strong>Jumlah Masuk:</strong> <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded font-bold">{{ $stockIn->jumlah_masuk }}</span> (Hubungi Admin untuk Retur/Batal jika Qty salah)</p>
                </div>

                <!-- Expiry Date -->
                <div class="mb-6">
                    <label for="tanggal_kedaluwarsa" class="block text-gray-700 font-semibold mb-2">
                        Tanggal Kedaluwarsa Batch <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="tanggal_kedaluwarsa" id="tanggal_kedaluwarsa" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_kedaluwarsa') border-red-500 @enderror" value="{{ old('tanggal_kedaluwarsa', $stockIn->tanggal_kedaluwarsa ? $stockIn->tanggal_kedaluwarsa->format('Y-m-d') : '') }}" required>
                    @error('tanggal_kedaluwarsa')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lot Number -->
                <div class="mb-6">
                    <label for="nomor_lot" class="block text-gray-700 font-semibold mb-2">
                        Nomor Lot / Batch (Opsional)
                    </label>
                    <input type="text" name="nomor_lot" id="nomor_lot" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nomor_lot') border-red-500 @enderror" value="{{ old('nomor_lot', $stockIn->nomor_lot) }}" placeholder="Contoh: BATCH-001">
                    @error('nomor_lot')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-gray-700 font-semibold mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('keterangan') border-red-500 @enderror" placeholder="Catatan tambahan...">{{ old('keterangan', $stockIn->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="check" class="inline h-5 w-5 mr-2"/> Update Data
                    </button>
                    <a href="{{ route('stok-masuk.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 rounded-lg text-center transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="x" class="inline h-5 w-5 mr-2"/> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
