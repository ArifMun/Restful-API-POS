<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Resources\ProdukResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();

        return response()->json(['produk' => ProdukResource::collection($produk), 'message' => 'Data berhasil ditampilkan'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'foto' => 'required',
            'nama' => 'required',
            'harga' => 'required',
            'stok' => 'required',
        ]);

        if ($validator->fails()) {
            return \response(['error' => $validator->errors(), 'Data tidak tersimpan!']);
        }

        $produk = Produk::create($data);

        return \response(['produk' => new ProdukResource($produk), 'message' => 'Data Berhasil Ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return \response(['produk' => new ProdukResource($produk), 'message' => 'Data Produk Berhasil Ditampilkan!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $produk->update($request->all());
        return \response(['produk' => new ProdukResource($produk), 'message' => 'Data Produk Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return \response(['message' => 'Data Berhasil Dihapus!']);
    }
}
