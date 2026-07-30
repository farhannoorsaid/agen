<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Seeder
        $user = User::firstOrCreate(
            ['email' => 'AgenHendi@gmail.com'],
            [
                'name' => 'Agen Hendi',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Supplier Seeder
        $supplier1 = Supplier::create([
            'nama_supplier' => 'PT Indofood Makmur',
            'alamat' => 'Jl. Sudirman No. 1, Jakarta',
            'no_hp' => '081234567890',
            'row_status' => 1
        ]);

        $supplier2 = Supplier::create([
            'nama_supplier' => 'CV Sembako Jaya',
            'alamat' => 'Jl. Merdeka No. 45, Bandung',
            'no_hp' => '089876543210',
            'row_status' => 1
        ]);

        // 3. Barang Seeder
        $barang1 = Barang::create([
            'supplier_id' => $supplier1->id,
            'nama_barang' => 'Indomie Goreng (Kardus)',
            'stok' => 50, // 60 masuk - 10 keluar
            'stok_minimum' => 10,
            'harga_jual' => 110000,
            'row_status' => 1
        ]);

        $barang2 = Barang::create([
            'supplier_id' => $supplier2->id,
            'nama_barang' => 'Beras Pandan Wangi 5Kg',
            'stok' => 20, // 20 masuk - 0 keluar
            'stok_minimum' => 5,
            'harga_jual' => 75000,
            'row_status' => 1
        ]);

        // 4. StockIn Seeder
        StockIn::create([
            'barang_id' => $barang1->id,
            'jumlah_masuk' => 60,
            'keterangan' => 'Stok awal dari pabrik',
            'user_id' => $user->id,
            'tanggal_kedaluwarsa' => Carbon::now()->addMonths(6),
            'nomor_lot' => 'LOT-001',
            'sisa' => 50 // Sisa 50 karena sudah terjual 10
        ]);

        StockIn::create([
            'barang_id' => $barang2->id,
            'jumlah_masuk' => 20,
            'keterangan' => 'Restock bulanan',
            'user_id' => $user->id,
            'tanggal_kedaluwarsa' => Carbon::now()->addMonths(3),
            'nomor_lot' => 'LOT-002',
            'sisa' => 20
        ]);

        // 5. StockOut Seeder (Penjualan)
        StockOut::create([
            'barang_id' => $barang1->id,
            'jumlah_terjual' => 10,
            'product_name_snapshot' => $barang1->nama_barang,
            'supplier_name_snapshot' => $supplier1->nama_supplier,
            'price_snapshot' => $barang1->harga_jual,
            'total_harga' => 10 * $barang1->harga_jual,
            'status_pembayaran' => 'lunas',
            'user_id' => $user->id,
        ]);
    }
}
