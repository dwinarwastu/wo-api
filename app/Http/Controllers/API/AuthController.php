<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:255',
            'email'    => 'required|unique:users',
            'alamat'   => 'required|string|max:255',
            'no_telp'  => 'required||max:14|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'nama.required'     => 'nama harus diisi',
            'email.required'    => 'email harus diisi',
            'alamat.required'   => 'alamat harus diisi',
            'no_telp.required'  => 'nomor telepon harus diisi',
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi',
            'password.min'      => 'password minimal 8',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'data tidak valid',
                'data' => $validator->errors()
            ], 422);
        }

        $user = New \App\Models\User();
        $user->nama     = $request->nama;
        $user->email    = $request->email;
        $user->alamat   = $request->alamat;
        $user->no_telp  = $request->no_telp;
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $nama_image = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($nama_image, PATHINFO_FILENAME);
            $fileName = $fileNameWithoutExtension.'.'.$image->getClientOriginalExtension();
            $path = public_path('/images/foto_users');
            $image->move($path, $fileName);
            $user->foto = $fileName;
        }
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => true,
            'message' => 'register berhasil',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'username harus diisi',
            'password.required' => 'password harus diisi',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => 'data tidak valid',
                'data' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response([
                'status' => false,
                'message' => 'username dan password tidak sesuai',
            ], 401);
        }

        $user = \App\Models\User::where('username', $request->username)->first();

        return response([
            'status' => true,
            'message' => 'login berhasil',
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'status' => true,
            'message' => 'logout berhasil',
        ]);
    }
}
