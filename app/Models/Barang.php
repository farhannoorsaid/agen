<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_barang",
        'kategori',
        "kode_barang",
        'stok',
        'satuan',
        'harga_beli',
        "stok_minimum",
        "harga_jual",
        "row_status",
    ];

    protected $casts = [
        "harga_jual" => "decimal:2",
    ];

    /**
     * Relasi ke Supplier (Many-to-Many)
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'barang_supplier');
    }

    /**
     * Relasi ke StockIn
     */
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Relasi ke StockOut
     */
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class);
    }

    /**
     * Scope untuk barang yang tidak diarsip
     */
    public function scopeActive($query)
    {
        return $query->where('row_status', 1);
    }
    
    public function scopeArchived($query)
    {
        return $query->where('row_status', 0);
    }

    /**
     * Scope untuk barang dengan stok rendah
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw("stok <= stok_minimum");
    }

    /**
     * Scope untuk barang hampir kedaluwarsa (< 30 hari)
     * Sekarang mengecek stock_ins yang belum terjual
     */
    public function scopeExpiringSoon($query)
{
    $today = now()->startOfDay();
    $limit = now()->addDays(30)->endOfDay();

    return $query->whereHas('stockIns', function ($q) use ($today, $limit) {
        $q->where('sisa', '>', 0)
          ->whereNotNull('tanggal_kedaluwarsa')
          ->whereBetween('tanggal_kedaluwarsa', [$today, $limit]);
    });
}
}
