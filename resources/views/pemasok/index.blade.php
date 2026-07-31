@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="box" class="inline h-6 w-6 mr-2 text-white"/> Data Pemasok</h1>
            <p class="text-blue-100 mt-2">Kelola informasi supplier/pemasok Anda dengan aman</p>
        </div>

        @if ($message = Session::get('success'))
            <!-- <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ $message }}
            </div> -->
            <x-alert-success />
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <a href="{{ route('pemasok.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-md inline-flex items-center">
                <x-icon name="plus" class="inline h-4 w-4 mr-2"/> Tambah Supplier
            </a>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Nama Supplier</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">Alamat</th>
                        <th class="px-6 py-4 text-left text-gray-700 font-semibold">No. HP</th>
                        <th class="px-6 py-4 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $item)
                        <tr class="border-b bg-gray-50 ">
                            <td class="px-6 py-4 font-semibold text-gray-500">
                                {{ $item->nama_supplier }}  
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $item->alamat  }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $item->no_hp ?? '-' }}</td>
                           
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('pemasok.edit', $item->id) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md text-sm inline-flex items-center transition">
                                        <x-icon name="edit" class="h-4 w-4 mr-1"/>
                                        Edit
                                    </a>
                                
                                    <form action="{{ route('pemasok.destroy', $item->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini? Data yang dihapus tidak dapat dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm inline-flex items-center transition">
                                            <x-icon name="trash" class="h-4 w-4 mr-1"/>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                Belum ada data supplier
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection