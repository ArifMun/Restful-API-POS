<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8',
            'role'      => 'required',
        ]);

        if ($validator->fails()) {
            return \response(['error' => $validator->errors(), 'Data tidak tersimpan!']);
        }

        if (!Auth::user() || Auth::user()->role == 'pembeli') {

            $user = User::create([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => 'pembeli',
            ]);
        } else if (Auth::user()->role == 'penjual') {

            $user = User::create([
                'nama'      => $request->nama,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role,
            ]);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['data' => $user, 'access_token' => $accessToken, 'message' => 'Data tersimpan!'], 201);
    }

    /**
     * Display the specified resource.
     */

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email'     => 'email|required',
            'password'  => 'required'
        ]);

        if (!\auth()->attempt($loginData)) {
            return \response(['message' => "user tidak ditemukan, silahkan cek kembali"], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return \response([
            'data' => \auth()->user(),
            'access_token' => $accessToken
        ]);
    }

    public function logout(Request $request)
    {
        $tokenDelete = \auth()->user()->tokens()->delete();
        return \response([
            'data' => \auth()->user(),
            'message' => 'anda berhasil logout'
        ]);
    }

    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
