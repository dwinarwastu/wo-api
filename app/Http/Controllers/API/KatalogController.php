<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    public function index()
    {
        $data["kategori"] = \App\Models\Kategori::all();
        if (auth()->check()) {
            $role = auth()->user()->role;
            $user = auth()->user()->id_user;
            if ($role == 1) {
                $penjual = DB::table('detail_penjual')->where('user_id', $user)->first()->id_detail_penjual;
                $data['penjual'] = \App\Models\Katalog::with('detailKatalog')->get()->where('detail_penjual_id', $penjual);
            }
        }
        $data["detail_katalog"] = \App\Models\Katalog::with('detailKatalog')->get();
        $data["role"] = auth()->user()->role;
        return response([
            'status' => true,
            'message' => 'Data katalog tersedia',
            'data' => $data
        ], 201);
    }

    public function create()
    {
        $idUser = auth()->user()->id_user;
        $data["penjual"] = DB::table('detail_penjual')->where('user_id', $idUser)->first();
        $data["users"] = DB::table('users')->where('id_user', $idUser)->first();

        return response([
            'status' => true,
            'message' => 'Create data katalog',
            'data' => $data
        ], 201);
    }

    public function store(Request $request)
    {
        $idUser = auth()->user()->id_user;
        $id_detail_penjual = DB::table('detail_penjual')->where('user_id', $idUser)->first()->id_detail_penjual;

        $katalog = new \App\Models\Katalog();
        $katalog->detail_penjual_id = $id_detail_penjual;
        $katalog->judul = $request->judul;
        $katalog->deskripsi = $request->deskripsi;
        // $katalog->metode_bayar = $request->metode_bayar;
        $katalog->save();

        $id_detail_katalog = DB::table('katalog')->where("detail_penjual_id", $id_detail_penjual)->orderBy("created_at", "desc")->pluck("id_katalog")->first();
        $detail_katalog = new \App\Models\DetailKatalog();
        $detail_katalog->katalog_id = $id_detail_katalog;
        $detail_katalog->judul_variasi = $request->judul_variasi;
        $detail_katalog->harga = $request->harga;
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $nama_image = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($nama_image, PATHINFO_FILENAME);
            $fileName = $fileNameWithoutExtension.'.'.$image->getClientOriginalExtension();
            $path = public_path('/images/gambar_detail_katalog');
            $image->move($path, $fileName);
            $detail_katalog->gambar = $fileName;
        }
        $detail_katalog->save();

        return response([
            'status' => true,
            'message' => 'Store katalog berhasil',
        ], 201);
    }

    public function edit($id)
    {
        $data["katalog"] = \App\Models\Katalog::find($id);
        $data["detail_katalog"] = \App\Models\DetailKatalog::where('katalog_id', $id)->get();
        return response([
            'status' => true,
            'message' => 'Edit data katalog',
            'data' => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $idUser = auth()->user()->id_user;
        $id_detail_penjual = DB::table('detail_penjual')->where('user_id', $idUser)->first()->id_detail_penjual;

        $katalog = \App\Models\Katalog::find($id);
        $katalog->detail_penjual_id = $id_detail_penjual;
        $katalog->judul = $request->judul;
        $katalog->deskripsi = $request->deskripsi;
        $katalog->metode_bayar = $request->metode_bayar;
        $katalog->save();

        $id_detail_katalog = DB::table('katalog')->where("detail_penjual_id", $id_detail_penjual)->orderBy("created_at", "desc")->pluck("id_katalog")->first();
        $detail_katalog = \App\Models\DetailKatalog::where('katalog_id', $id)->first();
        $detail_katalog->katalog_id = $id_detail_katalog;
        $detail_katalog->judul_variasi = $request->judul_variasi;
        $detail_katalog->harga = $request->harga;
        if ($request->hasFile('gambar')) {

            if ($detail_katalog->gambar) {
                $oldImagePath = public_path('/images/gambar_detail_katalog/' . $detail_katalog->gambar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('gambar');
            $nama_image = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($nama_image, PATHINFO_FILENAME);
            $fileName = $fileNameWithoutExtension.'.'.$image->getClientOriginalExtension();
            $path = public_path('/images/gambar_detail_katalog');
            $image->move($path, $fileName);
            $detail_katalog->gambar = $fileName;
        }
        $detail_katalog->save();

        return response([
            'status' => true,
            'message' => 'Update katalog berhasil',
        ], 201);
    }

    public function destroy($id)
    {
        $katalog = \App\Models\Katalog::find($id);
        $detail_katalog = \App\Models\DetailKatalog::where('katalog_id', $id)->get();

        foreach ($detail_katalog as $file) {
            if ($file->gambar) {
                $oldImagePath = public_path('/images/gambar_detail_katalog/' . $file->gambar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }

        foreach ($detail_katalog as $data) {
            $data->delete();
        }

        $katalog->delete();

        return response([
            'status' => true,
            'message' => 'Delete katalog berhasil',
        ], 201);
    }

    public function lihatJasa($id){
        $data['detail_katalog'] = Katalog::with('detailKatalog')->find($id);
        $data['detail_penjual'] = Katalog::with('detailPenjual.user')->find($id);
        return response([
            'status' => true,
            'message' => 'Data katalog tersedia',
            'data' => $data
        ]);
    }
}
