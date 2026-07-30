<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * FIFO Structure Update: Move tanggal_kedaluwarsa from barangs to stock_ins
     * This allows different batches of same product to have different expiry dates
     */
    public function up(): void
    {
        // Add expiry date column to stock_ins (batch-level expiry)
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->date('tanggal_kedaluwarsa')->nullable()->after('keterangan');
            $table->string('nomor_lot')->nullable()->after('tanggal_kedaluwarsa')->comment('Lot/Batch number dari supplier');
        });

        // Remove expiry date from barangs (not needed here anymore)
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('tanggal_kedaluwarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore expiry date to barangs
        Schema::table('barangs', function (Blueprint $table) {
            $table->date('tanggal_kedaluwarsa')->nullable();
        });

        // Remove FIFO columns from stock_ins
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kedaluwarsa', 'nomor_lot']);
        });
    }
};
