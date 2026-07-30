<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();

            // Relasi ke barang (untuk referensi, bukan join di laporan)
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Jumlah terjual
            $table->integer('jumlah_terjual');

            // === SNAPSHOT DATA SAAT TRANSAKSI (Sangat Penting!) ===
            // Disimpan agar laporan tidak berubah meski data barang diubah
            $table->string('product_name_snapshot');         // Nama barang saat transaksi
            $table->string('supplier_name_snapshot');        // Nama supplier saat transaksi
            $table->decimal('price_snapshot', 12, 2);        // Harga jual saat transaksi

            // Hitung otomatis: price_snapshot × jumlah_terjual
            $table->decimal('total_harga', 12, 2);

            // Status pembayaran
            $table->enum('status_pembayaran', ['lunas'])->default('lunas');

            // User yang input
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};
