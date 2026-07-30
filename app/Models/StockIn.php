<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_ins';

    protected $fillable = [
        'invoice_number',
        'barang_id',
        'jumlah_masuk',
        'keterangan',
        'user_id',
        'tanggal_kedaluwarsa',
        'nomor_lot',
        'sisa',
        'supplier_id',
    ];

    protected $casts = [
        'tanggal_kedaluwarsa' => 'date',
        'sisa' => 'integer',
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
     * Relasi ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}