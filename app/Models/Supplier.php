<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_hp',
        'row_status',
    ];

    /**
     * Relasi ke Barang
     */
    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_supplier');
    }
}
