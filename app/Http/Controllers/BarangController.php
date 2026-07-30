<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the barang.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->query('search');
        
        $query = Barang::where('row_status', 1)->with([
            'suppliers' => function ($q) {
                $q->withTrashed();
            },
            'stockIns' => function($q) {
                $q->where('sisa', '>', 0)->orderByRaw('ISNULL(tanggal_kedaluwarsa), tanggal_kedaluwarsa ASC');
            }
        ]);


        if ($search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        }

        $data = $query->get();

        return view('barang.index', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new barang.
     */
    public function create()
    {
        $suppliers = Supplier::whereNull('deleted_at')->get();
        return view('barang.create', compact('suppliers'));
    }

    /**
     * Store a newly created barang in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_ids' => 'required|array',
            'supplier_ids.*' => 'exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:50',
            'stok_minimum' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);
        
        $validated['row_status'] = 1;

        $barang = Barang::create($validated);
        
        if (isset($validated['supplier_ids'])) {
            $barang->suppliers()->sync($validated['supplier_ids']);
        }
        
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the barang.
     */
    public function edit($id)
    {
        $barang = Barang::with('suppliers')->active()->findOrFail($id);
        $suppliers = Supplier::whereNull('deleted_at')->get();
        return view('barang.edit', compact('barang', 'suppliers'));
    }

    /**
     * Update the barang in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::active()->findOrFail($id);

        $validated = $request->validate([
            'supplier_ids' => 'required|array',
            'supplier_ids.*' => 'exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:50',
            'stok_minimum' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang->update($validated);
        
        if (isset($validated['supplier_ids'])) {
            $barang->suppliers()->sync($validated['supplier_ids']);
        }
        
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Arsipkan barang (soft delete).
     */
    public function destroy($id)
    {
        // $barang = Barang::active()->findOrFail($id);
        
        $barang = Barang::findOrFail($id);
        
       
        $barang->update([
            'row_status' => 0
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Tampil daftar barang yang diarsip.
     */
 

    /**
     * Restore barang dari arsip.
     */

}
