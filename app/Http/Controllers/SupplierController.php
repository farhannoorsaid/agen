<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // $suppliers = Supplier::withTrashed()->get();
        $suppliers = Supplier::withTrashed()->where('row_status', 1)->get();

        return view('pemasok.index', compact('suppliers'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);

        // Supplier::create($request->all());

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'row_status' => 1, 
        ]);

        return redirect('/pemasok')->with('success', 'Pemasok berhasil ditambahkan');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pemasok.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);

        Supplier::findOrFail($id)->update($request->all());
        return redirect('/pemasok')->with('success', 'Pemasok berhasil diperbarui');
    }

    public function destroy($id)
{
    $supplier = Supplier::findOrFail($id);

    $supplier->forceDelete(); // atau delete() jika SoftDeletes sudah dihapus

    return redirect('/pemasok')
        ->with('success', 'Supplier berhasil dihapus.');
}

    
}
