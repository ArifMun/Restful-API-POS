<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
