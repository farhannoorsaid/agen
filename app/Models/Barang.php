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
        
    }

    /**
     * Scope untuk barang yang diarsip
     */
    public function scopeArchived($query)
    {
       
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
    return $query->whereHas('stockIns', function ($q) {
        $q->whereRaw('DATEDIFF(tanggal_kedaluwarsa, CURDATE()) BETWEEN 0 AND 30')
          ->where('sisa', '>', 0);
    });
}
}
