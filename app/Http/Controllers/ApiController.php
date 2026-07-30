<?php

namespace App\Http\Controllers;

use App\Models\Barang;

class ApiController extends Controller
{
    /**
     * Get barang info by ID (untuk AJAX di stok-masuk)
     */
    public function getBarang($id)
    {
        $barang = Barang::active()->findOrFail($id);
        
        return response()->json([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'harga_jual' => $barang->harga_jual,
            'supplier' => $barang->suppliers->count() > 0 ? $barang->suppliers->pluck('nama_supplier')->join(', ') : '-',
        ]);
    }
}
