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
        Schema::create('barang_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique pairing
            $table->unique(['barang_id', 'supplier_id']);
        });

        // Migrate existing data from barangs.supplier_id to barang_supplier
        \Illuminate\Support\Facades\DB::statement('INSERT INTO barang_supplier (barang_id, supplier_id, created_at, updated_at) SELECT id, supplier_id, NOW(), NOW() FROM barangs WHERE supplier_id IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_supplier');
    }
};
