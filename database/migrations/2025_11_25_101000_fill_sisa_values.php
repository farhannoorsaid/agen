<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set existing stock_ins.sisa = jumlah_masuk for backward compatibility
        DB::table('stock_ins')->update(['sisa' => DB::raw('jumlah_masuk')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: reset to 0
        DB::table('stock_ins')->update(['sisa' => 0]);
    }
};
