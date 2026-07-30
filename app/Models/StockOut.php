<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_outs';

    protected $fillable = [
        'invoice_number',
        'barang_id',
        'jumlah_terjual',
        'product_name_snapshot',
        'supplier_name_snapshot',
        'price_snapshot',
        'total_harga',
        'status_pembayaran',
        'user_id',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk harga per unit
     */
    public function getPricePerUnitAttribute()
    {
        return $this->price_snapshot / $this->jumlah_terjual;
    }
}
