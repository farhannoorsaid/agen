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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();

            // Relasi ke barang
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Data transaksi
            $table->integer('jumlah_masuk');
            $table->text('keterangan')->nullable();

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
        Schema::dropIfExists('stock_ins');
    }
};
