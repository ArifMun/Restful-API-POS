<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\KeranjangController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\AuthenticateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::apiResource('auth', AuthenticateController::class);
Route::post('auth/login', [AuthenticateController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::middleware(['penjual',])->group(function () {
        Route::apiResource('produk', ProdukController::class);
        Route::apiResource('transaksi', TransaksiController::class)->only('index', 'show');
    });
    Route::middleware(['pembeli',])->group(function () {
        Route::apiResource('produk', ProdukController::class)->only('index', 'show');

        Route::apiResource('keranjang', KeranjangController::class);
        Route::get('list-keranjang', [KeranjangController::class, 'listKeranjangPembeli']);
        Route::delete('list-keranjang/hapus-semua', [KeranjangController::class, 'hapusSemua']);

        Route::apiResource('transaksi', TransaksiController::class);
        Route::get('list-transaksi', [TransaksiController::class, 'listTransaksiPembeli']);
    });
    Route::post('auth/logout', [AuthenticateController::class, 'logout']);
});
