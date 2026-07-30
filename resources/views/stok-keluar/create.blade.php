@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 text-white p-8 rounded-lg shadow-lg mb-8">
            <h1 class="text-3xl font-bold"><x-icon name="cart" class="inline h-6 w-6 mr-3 text-white"/> Kasir (Point of Sale)</h1>
            <p class="text-green-100 mt-2">Tambah beberapa barang ke keranjang untuk satu transaksi penjualan</p>
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
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Pilih Barang</h2>
                
                <div class="mb-4">
                    <label for="barang_id" class="block text-gray-700 font-semibold mb-2">Barang</label>
                    <select id="barang_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" onchange="updateBarangInfo()">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" 
                                    data-nama="{{ $barang->nama_barang }}"
                                    data-stok="{{ $barang->stok }}"
                                    data-harga="{{ $barang->harga_jual }}">
                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 grid grid-cols-2 gap-2">
                    <div class="p-2 bg-blue-50 rounded border border-blue-200">
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Stok</p>
                        <p id="info-stok" class="text-lg font-bold text-blue-600">-</p>
                    </div>
                    <div class="p-2 bg-green-50 rounded border border-green-200">
                        <p class="text-[10px] text-gray-500 font-bold uppercase">Harga</p>
                        <p id="info-harga" class="text-lg font-bold text-green-600">-</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="jumlah_terjual" class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                    <input type="number" id="jumlah_terjual" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" value="1" min="1">
                </div>

                <button type="button" onclick="addToCart()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition shadow-md flex justify-center items-center gap-2">
                    <x-icon name="plus" class="inline h-4 w-4"/> Tambah ke Keranjang
                </button>
            </div>

            <!-- Kolom Kanan: Keranjang & Checkout -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Keranjang Belanja</h2>

                <form action="{{ route('stok-keluar.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="p-3 text-sm font-semibold text-gray-600">Barang</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-right">Harga</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-center">Qty</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-right">Subtotal</th>
                                    <th class="p-3 text-sm font-semibold text-gray-600 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <tr id="empty-cart-row">
                                    <td colspan="5" class="p-8 text-center text-gray-400">Keranjang masih kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-end border-t pt-4 mb-6">
                        <div class="text-gray-600">Total Item: <span id="total-items" class="font-bold">0</span></div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Grand Total</p>
                            <p class="text-4xl font-bold text-green-600" id="grand-total">Rp 0</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" id="btn-checkout" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-lg transition shadow-md text-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <x-icon name="check" class="inline h-5 w-5 mr-2"/> Checkout & Cetak Nota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
}

function updateBarangInfo() {
    const select = document.getElementById('barang_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value) {
        document.getElementById('info-stok').textContent = selectedOption.dataset.stok;
        document.getElementById('info-harga').textContent = formatCurrency(selectedOption.dataset.harga);
    } else {
        document.getElementById('info-stok').textContent = '-';
        document.getElementById('info-harga').textContent = '-';
    }
}

function addToCart() {
    const select = document.getElementById('barang_id');
    const qtyInput = document.getElementById('jumlah_terjual');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!select.value) {
        alert('Pilih barang terlebih dahulu!');
        return;
    }
    
    const qty = parseInt(qtyInput.value);
    if (qty < 1 || isNaN(qty)) {
        alert('Jumlah harus lebih dari 0!');
        return;
    }
    
    const stok = parseInt(selectedOption.dataset.stok);
    const harga = parseFloat(selectedOption.dataset.harga);
    const id = select.value;
    const nama = selectedOption.dataset.nama;
    
    // Check if already in cart
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        if (existingItem.qty + qty > stok) {
            alert('Stok tidak mencukupi!');
            return;
        }
        existingItem.qty += qty;
    } else {
        if (qty > stok) {
            alert('Stok tidak mencukupi!');
            return;
        }
        cart.push({ id, nama, harga, qty, stok });
    }
    
    // Reset form
    select.value = "";
    qtyInput.value = 1;
    updateBarangInfo();
    
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const tbody = document.getElementById('cart-body');
    const totalItemsEl = document.getElementById('total-items');
    const grandTotalEl = document.getElementById('grand-total');
    const btnCheckout = document.getElementById('btn-checkout');
    
    tbody.innerHTML = '';
    
    if (cart.length === 0) {
        tbody.innerHTML = `<tr id="empty-cart-row"><td colspan="5" class="p-8 text-center text-gray-400">Keranjang masih kosong</td></tr>`;
        totalItemsEl.textContent = '0';
        grandTotalEl.textContent = 'Rp 0';
        btnCheckout.disabled = true;
        return;
    }
    
    let totalQty = 0;
    let grandTotal = 0;
    
    cart.forEach((item, index) => {
        const subtotal = item.qty * item.harga;
        totalQty += item.qty;
        grandTotal += subtotal;
        
        const tr = document.createElement('tr');
        tr.className = 'border-b hover:bg-gray-50';
        tr.innerHTML = `
            <td class="p-3">
                <div class="font-medium text-gray-800">${item.nama}</div>
                <div class="text-xs text-gray-500">Stok: ${item.stok}</div>
                <input type="hidden" name="items[${index}][barang_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][jumlah_terjual]" value="${item.qty}">
            </td>
            <td class="p-3 text-right text-sm">${formatCurrency(item.harga)}</td>
            <td class="p-3 text-center font-bold">${item.qty}</td>
            <td class="p-3 text-right font-bold text-green-600">${formatCurrency(subtotal)}</td>
            <td class="p-3 text-center">
                <button type="button" onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    totalItemsEl.textContent = totalQty;
    grandTotalEl.textContent = formatCurrency(grandTotal);
    btnCheckout.disabled = false;
}
</script>
@endsection
