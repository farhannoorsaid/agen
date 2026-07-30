@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="edit" class="inline h-6 w-6 mr-2 text-white"/> Edit Barang</h1>
            <p class="text-blue-100 mt-2">Ubah informasi data barang</p>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')



                <!-- Supplier Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Pilih Supplier (Bisa lebih dari 1) <span class="text-red-600">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 border border-gray-300 rounded-lg p-4 bg-gray-50 h-40 overflow-y-auto">
                        @php
                            $selectedSupplierIds = $barang->suppliers->pluck('id')->toArray();
                        @endphp
                        @foreach($suppliers as $supplier)
                            <label class="flex items-center space-x-3 bg-white p-2 border rounded hover:bg-blue-50 cursor-pointer">
                                <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}" 
                                       class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                       {{ in_array($supplier->id, old('supplier_ids', $selectedSupplierIds)) ? 'checked' : '' }}>
                                <span class="text-gray-700">{{ $supplier->nama_supplier }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('supplier_ids')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    @error('supplier_ids.*')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Name -->
                <div class="mb-6">
                    <label for="nama_barang" class="block text-gray-700 font-semibold mb-2">
                        Nama Barang <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama_barang" id="nama_barang" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_barang') border-red-500 @enderror" value="{{ $barang->nama_barang }}" required>
                    @error('nama_barang')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock, Minimum Stock, Satuan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="stok" class="block text-gray-700 font-semibold mb-2">
                            Stok <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="stok" id="stok" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror" value="{{ $barang->stok }}" min="0" required>
                        @error('stok')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stok_minimum" class="block text-gray-700 font-semibold mb-2">
                            Stok Minimum <span class="text-red-600">*</span>
                        </label>
                        <input type="number" name="stok_minimum" id="stok_minimum" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok_minimum') border-red-500 @enderror" value="{{ $barang->stok_minimum }}" min="1" required>
                        @error('stok_minimum')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="satuan" class="block text-gray-700 font-semibold mb-2">
                            Satuan
                        </label>
                        <select name="satuan" id="satuan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('satuan') border-red-500 @enderror">
                            <option value="">-- Pilih Satuan --</option>
                            <option value="Kg" {{ (old('satuan', $barang->satuan) == 'Kg') ? 'selected' : '' }}>Kilogram (Kg)</option>
                            <option value="Gram" {{ (old('satuan', $barang->satuan) == 'Gram') ? 'selected' : '' }}>Gram (g)</option>
                            <option value="Liter" {{ (old('satuan', $barang->satuan) == 'Liter') ? 'selected' : '' }}>Liter (L)</option>
                            <option value="Ml" {{ (old('satuan', $barang->satuan) == 'Ml') ? 'selected' : '' }}>Mililiter (ml)</option>
                            <option value="Pcs" {{ (old('satuan', $barang->satuan) == 'Pcs') ? 'selected' : '' }}>Pieces (Pcs)</option>
                            <option value="Box" {{ (old('satuan', $barang->satuan) == 'Box') ? 'selected' : '' }}>Box</option>
                            <option value="Dus" {{ (old('satuan', $barang->satuan) == 'Dus') ? 'selected' : '' }}>Kardus (Dus)</option>
                            <option value="Pak" {{ (old('satuan', $barang->satuan) == 'Pak') ? 'selected' : '' }}>Pak / Pack</option>
                            <option value="Lusin" {{ (old('satuan', $barang->satuan) == 'Lusin') ? 'selected' : '' }}>Lusin</option>
                            <option value="Karung" {{ (old('satuan', $barang->satuan) == 'Karung') ? 'selected' : '' }}>Karung</option>
                        </select>
                        @error('satuan')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Selling Price -->
                <div class="mb-6">
                    <label for="harga_jual" class="block text-gray-700 font-semibold mb-2">
                        Harga Jual (Rp) <span class="text-red-600">*</span>
                    </label>
                    <input type="number" name="harga_jual" id="harga_jual" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_jual') border-red-500 @enderror" value="{{ $barang->harga_jual }}" min="0" required>
                    @error('harga_jual')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Banner -->
                <div class="mb-6 p-5 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong><x-icon name="info" class="inline h-4 w-4 mr-2 text-blue-800"/> Tanggal Kedaluwarsa:</strong> Input tanggal kedaluwarsa saat melakukan <strong>Stok Masuk</strong>, bukan di sini. Ini memungkinkan setiap batch barang memiliki exp.date yang berbeda.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="check" class="inline h-5 w-5 mr-2"/> Simpan Perubahan
                    </button>
                    <a href="{{ route('barang.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 rounded-lg text-center transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="x" class="inline h-5 w-5 mr-2"/> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
