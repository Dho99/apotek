<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{

    public function index()
    {
        return view('products.list', [
            'title' => 'Daftar Produk',
            'products' => Produk::with('supplier', 'golongan')->get(),
        ]);
    }

    public function getExpiredProduct()
    {
        return view('products.kadaluarsa', [
            'title' => 'Produk Kadaluarsa',
            'products' => Produk::where('expDate', '<=', now())->with('supplier', 'golongan')->get(),
        ]);
    }

    public function updateExpiredDate(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create',[
            'title' => 'Tambah Produk',
            'categories' => Kategori::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->now = now();

        $validate = $request->validate([
            'productCode' => 'required|unique:produks,kode',
            'productName' => 'required',
            'productImagepaths' => 'required|array',
            'productCategory' =>'required',
            'productSupplier' => 'required',
            'expDate' => 'required|after:now',
            'productDescription' => 'required|min:8|max:300'
        ]);

        return response()->json(['data' => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
