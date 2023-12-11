<?php

namespace App\Http\Controllers\Api;

use App\Models\Keranjang;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TransaksiResource;
use App\Mail\SendEmail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::all();

        return \response([
            'message' => 'List Data Transaksi',
            'data'    => new TransaksiResource($transaksi)
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'status'        => 'required',
            'produk_id' => [
                'required',
                Rule::exists('keranjangs', 'produk_id')->where(function ($query) {
                    $query->where('pembeli_id', Auth::user()->id);
                }),
            ],
        ]);

        $user = Auth::user()->id;
        $orderID = 'ORD' . now()->format('YmdHis') . Str::random(4);

        try {
            $tract = new Transaksi();
            $keranjang = $tract->CekProduk($request->produk_id);
        } catch (\Exception $e) {
            return response(['error' => 'Produk tidak ditemukan di keranjang!'], 404);
        }

        $produk_id = $keranjang->produk_id;

        if ($validator->fails()) {
            return \response(['error' => $validator->errors(), 'Data tidak tersimpan!'], 404);
        }

        $transaksi = Transaksi::create([
            'order_id'      => $orderID,
            'produk_id'     => $produk_id,
            'pembeli_id'    => $user,
            'total_harga'   => $keranjang->produk->harga,
            'status'        => $request->status,
        ]);

        Mail::to($request->user()->email)->send(new SendEmail($transaksi));

        $keranjang->delete();

        return \response([
            'message' => 'Transaksi berhasil dilakukan, cek email anda untuk konfirmasi',
            'data'    => new TransaksiResource($transaksi)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        return \response([
            'message' => 'Daftar Pesanan Anda',
            'data'    => new TransaksiResource($transaksi)
        ], 201);
    }

    public function listTransaksiPembeli()
    {
        $user = Auth::user();

        $transaksi = User::with('transaksi')->find($user->id);

        return \response([
            'message'  => 'Transaksi Anda',
            'data'     => new TransaksiResource($transaksi)
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
