<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();

            // Relasi ke pemasok
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

            // Data utama barang
            $table->string('nama_barang');
            $table->integer('stok');
            $table->integer('stok_minimum')->default(5);
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->decimal('harga_jual', 12, 2);

            // Status arsip

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
