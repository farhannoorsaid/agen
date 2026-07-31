@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="download" class="inline h-6 w-6 mr-3 text-white"/> Transaksi Pembelian</h1>
            <p class="text-blue-100 mt-2">Catat barang yang diterima dari supplier dengan tracking exp.date</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri: Input Barang -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Input Barang</h2>
                
                <div class="mb-4">
                    <label for="supplier_id" class="block text-gray-700 font-semibold mb-2">Pemasok (Supplier) <span class="text-red-600">*</span></label>
                    <select id="supplier_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="filterBarangBySupplier()">
                        <option value="">-- Pilih Supplier Terlebih Dahulu --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" data-nama="{{ $supplier->nama_supplier }}">
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="barang_id" class="block text-gray-700 font-semibold mb-2">Barang <span class="text-red-600">*</span></label>
                    <select id="barang_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateBarangInfo()" disabled>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" 
                                    data-nama="{{ $barang->nama_barang }}"
                                    data-stok="{{ $barang->stok }}"
                                    data-satuan="{{ $barang->satuan ?? 'unit' }}"
                                    data-supplier-ids="{{ json_encode($barang->suppliers->pluck('id')) }}">
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="jumlah_masuk" class="block text-gray-700 font-semibold mb-2">Jumlah Masuk <span class="text-red-600">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="number" id="jumlah_masuk" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="1" min="1">
                        <span id="label-satuan" class="text-gray-600 font-medium px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg whitespace-nowrap min-w-[80px] text-center">-</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tanggal_kedaluwarsa" class="block text-gray-700 font-semibold mb-2">Tanggal Kedaluwarsa <span class="text-red-600">*</span></label>
                    <input type="date" id="tanggal_kedaluwarsa" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" min="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="mb-4">
                    <label for="nomor_lot" class="block text-gray-700 font-semibold mb-2">Nomor Lot / Batch</label>
                    <input type="text" id="nomor_lot" value="LOT-" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: LOT-001">
                </div>

                <div class="mb-6">
                    <label for="keterangan" class="block text-gray-700 font-semibold mb-2">Keterangan</label>
                    <textarea id="keterangan" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Catatan..."></textarea>
                </div>

                <button type="button" onclick="addToCart()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition shadow-md flex justify-center items-center gap-2">
                    <x-icon name="plus" class="inline h-4 w-4"/> Tambah ke List Pembelian
                </button>
            </div>

            <!-- Kolom Kanan: Keranjang & Checkout -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Daftar Barang Masuk</h2>

                <!-- FIFO Warning -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded">
                    <p class="text-sm text-blue-800">
                        <strong><x-icon name="info" class="inline h-4 w-4 mr-2 text-blue-800"/> Sistem FEFO (First-Expired, First-Out):</strong> Saat terjadi penjualan, sistem otomatis mengeluarkan barang dengan tanggal kedaluwarsa paling dekat terlebih dahulu. Pastikan tanggal kedaluwarsa diinput dengan benar!
                    </p>
                </div>

                <form action="{{ route('stok-masuk.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Barang & Supplier</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-center">Qty</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Kadaluwarsa</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600">Catatan</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr id="empty-cart-row">
                                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada barang yang ditambahkan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-end border-t pt-4 mb-6">
                        <div class="text-gray-600">Total Macam Barang: <span id="total-items" class="font-bold">0</span></div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Total Qty Masuk</p>
                            <p class="text-3xl font-bold text-blue-600" id="grand-total-qty">0</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" id="btn-checkout" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-lg transition shadow-md text-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <x-icon name="check" class="inline h-5 w-5 mr-2"/> Simpan Transaksi Pembelian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function updateBarangInfo() {
    const barangSelect = document.getElementById('barang_id');
    const labelSatuan = document.getElementById('label-satuan');
    
    if (barangSelect.value) {
        const option = barangSelect.options[barangSelect.selectedIndex];
        labelSatuan.textContent = option.dataset.satuan || '-';
    } else {
        labelSatuan.textContent = '-';
    }
}

function filterBarangBySupplier() {
    const supplierSelect = document.getElementById('supplier_id');
    const barangSelect = document.getElementById('barang_id');
    const selectedSupplierId = supplierSelect.value;
    
    // Reset selection
    barangSelect.value = "";
    updateBarangInfo();
    
    if (!selectedSupplierId) {
        barangSelect.disabled = true;
        return;
    }
    
    barangSelect.disabled = false;
    
    // Loop through options and hide/show
    for (let i = 1; i < barangSelect.options.length; i++) {
        const option = barangSelect.options[i];
        if (option.dataset.supplierIds) {
            const supplierIds = JSON.parse(option.dataset.supplierIds);
            if (supplierIds.includes(parseInt(selectedSupplierId))) {
                option.style.display = "";
                option.disabled = false;
            } else {
                option.style.display = "none";
                option.disabled = true;
            }
        }
    }
}

function addToCart() {
    const barangSelect = document.getElementById('barang_id');
    const supplierSelect = document.getElementById('supplier_id');
    const qtyInput = document.getElementById('jumlah_masuk');
    const tglInput = document.getElementById('tanggal_kedaluwarsa');
    const lotInput = document.getElementById('nomor_lot');
    const ketInput = document.getElementById('keterangan');
    
    if (!barangSelect.value) {
        alert('Pilih barang terlebih dahulu!');
        return;
    }
    
    if (!supplierSelect.value) {
        alert('Pilih supplier terlebih dahulu!');
        return;
    }

    if (!tglInput.value) {
        alert('Pilih tanggal kedaluwarsa!');
        return;
    }
    
    const qty = parseInt(qtyInput.value);
    if (qty < 1 || isNaN(qty)) {
        alert('Jumlah masuk harus lebih dari 0!');
        return;
    }
    
    const id = barangSelect.value;
    const option = barangSelect.options[barangSelect.selectedIndex];
    const nama = option.dataset.nama;
    const satuan = option.dataset.satuan;
    
    const supplierId = supplierSelect.value;
    const supplierNama = supplierSelect.options[supplierSelect.selectedIndex].dataset.nama;

    cart.push({ 
        id, 
        nama, 
        satuan,
        supplierId,
        supplierNama,
        qty, 
        tgl: tglInput.value,
        lot: lotInput.value,
        ket: ketInput.value
    });
    
    // Reset partial form (keep supplier to speed up same-supplier entry)
    barangSelect.value = "";
    qtyInput.value = 1;
    tglInput.value = "";
    lotInput.value = "";
    ketInput.value = "";
    
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cart-body');
    const totalItemsEl = document.getElementById('total-items');
    const grandTotalQtyEl = document.getElementById('grand-total-qty');
    const btnCheckout = document.getElementById('btn-checkout');
    
    tbody.innerHTML = '';
    
    if (cart.length === 0) {
        tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="5" class="p-8 text-center text-gray-400">Belum ada barang yang ditambahkan</td></tr>`;
        totalItemsEl.textContent = '0';
        grandTotalQtyEl.textContent = '0';
        btnCheckout.disabled = true;
        return;
    }
    
    let totalQty = 0;
    
    cart.forEach((item, index) => {
        totalQty += item.qty;
        
        const tr = document.createElement('tr');
        tr.className = 'border-b hover:bg-gray-50';
        tr.innerHTML = `
            <td class="p-3">
                <div class="font-medium text-gray-800">${item.nama}</div>
                <div class="text-xs text-gray-500">Supplier: ${item.supplierNama}</div>
                
                <input type="hidden" name="items[${index}][barang_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][supplier_id]" value="${item.supplierId}">
                <input type="hidden" name="items[${index}][jumlah_masuk]" value="${item.qty}">
                <input type="hidden" name="items[${index}][tanggal_kedaluwarsa]" value="${item.tgl}">
                <input type="hidden" name="items[${index}][nomor_lot]" value="${item.lot}">
                <input type="hidden" name="items[${index}][keterangan]" value="${item.ket}">
            </td>
            <td class="p-3 text-center font-bold text-blue-600">${item.qty} <span class="text-xs text-gray-500 font-normal">${item.satuan}</span></td>
            <td class="p-3 text-sm">${item.tgl}</td>
            <td class="p-3 text-xs text-gray-600">${item.lot ? 'Lot: '+item.lot+'<br>' : ''}${item.ket ? item.ket : '-'}</td>
            <td class="p-3 text-center">
                <button type="button" onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    totalItemsEl.textContent = cart.length;
    grandTotalQtyEl.textContent = totalQty;
    btnCheckout.disabled = false;
}
</script>
@endsection
