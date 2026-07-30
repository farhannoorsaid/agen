<nav class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-6">
        <a href="/dashboard" class="font-bold text-lg">Agen Hendi</a>
        <a href="/dashboard">Dashboard</a>
        <a href="/barang">Data Barang</a>
        <a href="/pemasok">Data Pemasok</a>
        <a href="/stok-masuk">Transaksi Pembelian</a>
        <a href="/stok-keluar">Transaksi Penjualan</a>
        <a href="/laporan">Laporan</a>
    </div>

    <div class="flex items-center space-x-4">
        <span>{{ auth()->user()->name }}</span>
        <form action="/logout" method="POST">
            @csrf
            <button class="border px-3 py-1 rounded">Logout</button>
        </form>
    </div>
</nav>
