@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold">Edit Supplier</h1>
            <p class="text-blue-100 mt-2">Ubah informasi supplier</p>
        </div>

        <div class="bg-white rounded-lg shadow p-8">
            <form action="{{ route('pemasok.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="nama_supplier" class="block text-gray-700 font-semibold mb-2">Nama Supplier *</label>
                    <input type="text" name="nama_supplier" id="nama_supplier" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_supplier') border-red-500 @enderror" value="{{ $supplier->nama_supplier }}" required>
                    @error('nama_supplier')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="alamat" class="block text-gray-700 font-semibold mb-2">Alamat *</label>
                    <input type="text" name="alamat" id="alamat" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat') border-red-500 @enderror" value="{{ $supplier->alamat }}" required>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="no_hp" class="block text-gray-700 font-semibold mb-2">No. HP *</label>
                    <input type="text" name="no_hp" id="no_hp" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_hp') border-red-500 @enderror" value="{{ $supplier->no_hp }}" required>
                    @error('no_hp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition">
                        <x-icon name="check" class="inline h-4 w-4"/> Simpan Perubahan
                    </button>
                    <a href="{{ route('pemasok.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 rounded-lg text-center transition">
                        <x-icon name="x" class="inline h-4 w-4"/> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection