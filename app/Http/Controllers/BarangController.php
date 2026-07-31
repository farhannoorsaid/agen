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
    public function index(Request $request)
{
    $search = $request->search;

    $data = Barang::with([
            'suppliers',
            'stockIns' => function ($query) {
                $query->orderBy('tanggal_kedaluwarsa', 'asc')
                      ->orderBy('created_at', 'asc');
            }
        ])
        ->when($search, function ($query, $search) {
            $query->where('nama_barang', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('barang.index', compact('data'));
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
            'satuan' => 'required|string|max:50',
            'stok_minimum' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'satuan.required' => 'Satuan wajib dipilih.',
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
    public function destroy(Barang $barang)
    {
        $barang->delete();
    
        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

  

}
