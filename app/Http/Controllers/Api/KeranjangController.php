<?php

namespace App\Http\Controllers\Api;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\KeranjangResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $keranjang = User::with(['keranjang', 'produk']);

        return \response([
            'message' => 'List Keranjang',
            'data' => new KeranjangResource($keranjang)
        ], 201);
    }

    public function listKeranjangPembeli()
    {
        $user = Auth::user();

        $keranjang = User::with('keranjang')->find($user->id);

        return \response([
            'message' => 'List Keranjang',
            'data' => new KeranjangResource($keranjang),
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Data tidak tersimpan!']);
        }

        $keranjang = Keranjang::create([
            'pembeli_id' => Auth::user()->id,
            'produk_id'  => $request->produk_id
        ]);

        $keranjang->load('produk');

        return response([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => new KeranjangResource($keranjang)
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Keranjang $keranjang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keranjang $keranjang)
    {
        $keranjang->update(
            [
                'produk_id' => $request->produk_id,
            ]
        );

        return response([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => new KeranjangResource($keranjang)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();
        return response([
            'message' => 'Produk berhasil dihapus!',
            'data' => new KeranjangResource($keranjang)
        ], 201);
    }

    public function hapusSemua(Keranjang $keranjang)
    {
        $user = Auth::user();

        $keranjang = $user->keranjang;

        $user->keranjang()->delete();
        return response([
            'message' => 'Semua Produk berhasil dihapus!',
            'data' => new KeranjangResource($keranjang)
        ], 201);
    }
}