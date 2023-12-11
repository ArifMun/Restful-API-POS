<?php

namespace App\Http\Controllers\API;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Resources\ProdukResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{

    public function index()
    {
        $produk = Produk::all();

        return response([
            'message' => 'Data Produk berhasil ditampilkan',
            'data' => new ProdukResource($produk)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'foto'  => 'required|image',
            'nama'  => 'required',
            'harga' => 'required',
            'stok'  => 'required',
        ]);

        if ($validator->fails()) {
            return \response(['error' => $validator->errors(), 'Data tidak tersimpan!']);
        }

        $image_path = $request->file('foto')->store('foto', 'public');

        $produk = Produk::create([
            'foto'  => $image_path,
            'nama'  => $request->nama,
            'harga' => $request->harga,
            'stok'  => $request->stok,
        ]);

        return \response([
            'message' => 'Data Berhasil Ditambahkan',
            'data' => new ProdukResource($produk)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return \response([
            'message' => 'Data Produk Berhasil Ditampilkan!',
            'data' => new ProdukResource($produk)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $produk->update($request->all());
        return \response([
            'data' => new ProdukResource($produk),
            'message' => 'Data Produk Berhasil Diubah!'
        ]);
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
