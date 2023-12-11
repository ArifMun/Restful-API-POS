<?php

namespace App\Models;


use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'produk_id',
        'pembeli_id',
        'total_harga',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pembeli_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function CekProduk($produk_id)
    {
        $user = Auth::user();
        return
            Keranjang::with('produk')
            ->where('pembeli_id', $user->id)
            ->where('produk_id', $produk_id)
            ->firstOrFail();
    }
}
