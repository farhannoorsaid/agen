@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ showModal: false, selectedItem: null }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="box" class="inline h-6 w-6 mr-2 text-white"/> Data Barang</h1>
            <p class="text-blue-100 mt-2">Kelola master data barang dan inventori Anda</p>
        </div>

        @if ($message = Session::get('success'))
            <!-- <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
                <x-icon name="check" class="inline h-4 w-4 mr-2 text-green-600"/> {{ $message }}
            </div> -->
            <x-alert-success />
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
            <div class="flex gap-2 w-full md:w-auto">
                <form action="{{ route('barang.index') }}" method="GET" class="flex items-center gap-2 w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="border border-gray-300 rounded-lg px-4 py-2 w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm">
                        <x-icon name="search" class="inline h-4 w-4"/>
                    </button>
                </form>
            </div>
            <a href="{{ route('barang.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-md inline-flex items-center">
                <x-icon name="plus" class="inline h-4 w-4 mr-2"/> Tambah Barang
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Barang</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Supplier</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Satuan</th>
                        <th class="px-6 py-4 text-right text-gray-700 font-semibold">Harga Jual</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr class="border-b hover:bg-gray-50 transition {{ $item->stok <= $item->stok_minimum ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($item->suppliers->count() > 0)
                                    {{ $item->suppliers->pluck('nama_supplier')->join(', ') }}
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            {{-- <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $item->stok <= $item->stok_minimum ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                    {{ $item->stok }}
                                </span>
                            </td> --}}
                            <td class="px-6 py-4 text-center text-gray-700">{{ $item->satuan }}</td>
                            <td class="px-6 py-4 text-right text-gray-800 font-semibold">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button type="button" @click="selectedItem = {{ json_encode($item) }}; showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition shadow-sm inline-flex items-center">
                                        <x-icon name="eye" class="inline h-4 w-4 mr-1"/> Detail
                                    </button> ||
                                    <a href="{{ route('barang.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition shadow-sm inline-flex items-center">
                                        <x-icon name="edit" class="inline h-4 w-4 mr-1"/> Edit
                                    </a> ||
                                    <form action="{{ route('barang.destroy', $item) }}"
      method="POST"
      class="inline"
      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

    @csrf
    @method('DELETE')

    <button type="submit"
        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition shadow-sm inline-flex items-center">

        <x-icon name="archive" class="h-4 w-4 mr-1"/>
        Delete
    </button>
</form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Riwayat Pembelian -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="showModal = false"></div>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-10">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900 border-b pb-3 mb-4 flex items-center" id="modal-title">
                                <x-icon name="clock" class="inline h-5 w-5 mr-2 text-blue-600"/>
                                Riwayat & Kedaluwarsa: <span class="ml-2 text-blue-700" x-text="selectedItem?.nama_barang"></span>
                            </h3>
                            <div class="mt-4 max-h-[60vh] overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100 sticky top-0">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">Tgl Pembelian</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-700 uppercase">Jml Beli</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-center text-gray-700 uppercase">Sisa Stok</th>
                                            <th scope="col" class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-gray-700 uppercase">Kedaluwarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="history in selectedItem?.stock_ins" :key="history.id">
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" x-text="new Date(history.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900" x-text="history.jumlah_masuk"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-blue-600" x-text="history.sisa"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" 
                                                    :class="{
                                                        'text-red-600': history.tanggal_kedaluwarsa && new Date(history.tanggal_kedaluwarsa) <= new Date(new Date().setDate(new Date().getDate() + 30)),
                                                        'text-green-600': history.tanggal_kedaluwarsa && new Date(history.tanggal_kedaluwarsa) > new Date(new Date().setDate(new Date().getDate() + 30)),
                                                        'text-gray-500': !history.tanggal_kedaluwarsa
                                                    }">
                                                    <span x-text="history.tanggal_kedaluwarsa ? new Date(history.tanggal_kedaluwarsa).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '-'"></span>
                                                    <span x-show="history.tanggal_kedaluwarsa && new Date(history.tanggal_kedaluwarsa) <= new Date(new Date().setDate(new Date().getDate() + 30))" class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        ⚠️ Dekat
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="!selectedItem?.stock_ins || selectedItem.stock_ins.length === 0">
                                            <td colspan="4" class="px-6 py-8 whitespace-nowrap text-sm text-center text-gray-500 bg-gray-50">Belum ada riwayat pembelian untuk barang ini.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition" @click="showModal = false">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection