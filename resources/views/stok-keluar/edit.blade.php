@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="edit" class="inline h-6 w-6 mr-2 text-white"/> Edit Transaksi Penjualan</h1>
            <p class="text-green-100 mt-2">Perbarui status pembayaran penjualan</p>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <form action="{{ route('stok-keluar.update', $invoiceNumber) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <!-- Readonly Info -->
                <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2"><strong>Nomor Nota:</strong> {{ $invoiceNumber }}</p>
                    <p class="text-sm text-gray-700 mb-2"><strong>Total Item:</strong> {{ $stockOuts->sum('jumlah_terjual') }} barang ({{ $stockOuts->count() }} jenis)</p>
                    <p class="text-sm text-gray-700"><strong>Grand Total:</strong> <span class="bg-green-200 text-green-800 px-2 py-1 rounded font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span></p>
                </div>

                <!-- Status Pembayaran -->
                <div class="mb-6">
                    <label for="status_pembayaran" class="block text-gray-700 font-semibold mb-2">
                        Status Pembayaran <span class="text-red-600">*</span>
                    </label>
                    <select name="status_pembayaran" id="status_pembayaran" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_pembayaran') border-red-500 @enderror" required>
                        <option value="lunas" {{ $statusPembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ $statusPembayaran === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                    @error('status_pembayaran')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="check" class="inline h-5 w-5 mr-2"/> Simpan Perubahan
                    </button>
                    <a href="{{ route('stok-keluar.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 rounded-lg text-center transition shadow-md inline-flex items-center justify-center">
                        <x-icon name="x" class="inline h-5 w-5 mr-2"/> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
