<?php

namespace App\Http\Controllers\API;

use App;
use App\Http\Controllers\Controller;
use App\Models\DetailKatalog;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    public function index()
    {
        Auth::shouldUse('sanctum');

        $data["kategori"] = \App\Models\Kategori::all();
        if (auth()->check()) {
            $data['role'] = auth()->user()->role;
            $user = auth()->user()->id_user;
            if ($data['role'] == 1) {
                $penjual = DB::table('detail_penjual')->where('user_id', $user)->first()->id_detail_penjual;
                $data['penjual'] = \App\Models\Katalog::with('detailKatalog')->get()->where('detail_penjual_id', $penjual);
            }
        }else{
            $data['role'] = 'guest';
        }
        $data["detail_katalog"] = \App\Models\Katalog::with('detailKatalog')->get();
        foreach ($data["detail_katalog"] as $katalog){
            $data["nama_toko"] = \App\Models\DetailPenjual::where('id_detail_penjual', $katalog->detail_penjual_id)->first()->nama_toko;
        }
        $data["user"] = auth()->user();
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
        Auth::shouldUse('sanctum');

        $data['detail_katalog'] = Katalog::with('detailKatalog')->find($id);
        $data['detail_penjual'] = Katalog::with('detailPenjual.user')->find($id);
        if(auth()->check()){
            $data['role'] = auth()->user()->role;
        }else{
            $data['role'] = 'guest';
        }
        $data['user'] = auth()->user();

        return response([
            'status' => true,
            'message' => 'Data katalog tersedia',
            'data' => $data
        ]);
    }

    public function profile()
    {
        $data['profile'] = auth()->user();

        return response([
            'status' => true,
            'message' => 'Data profile tersedia',
            'data' => $data
        ]);

    }

    public function account()
    {
        $data['account'] = auth()->user();

        return response([
            'status' => true,
            'message' => 'Data account tersedia',
            'data' => $data
        ]);
    }

    public function pesan($id)
    {
        $data['katalog'] = DetailKatalog::with('katalog')->where('katalog_id', $id)->get();
        $data['detail_penjual'] = DetailKatalog::with('katalog.detailPenjual.user')->where('katalog_id', $id)->first();
        $data['user'] = auth()->user();

        return response([
            'status' => true,
            'message' => 'Data katalog tersedia',
            'data' => $data
        ]);
    }

    public function store_penyedia_jasa(Request $request){
        // dd(auth()->user()->id_user);
        $penyedia_jasa = new App\Models\DetailPenjual();
        $penyedia_jasa->user_id = auth()->user()->id_user;
        $penyedia_jasa->nama_toko = $request->nama_toko;
        $penyedia_jasa->alamat = $request->alamat . " " . $request->kota . ", " . $request->provinsi;
        $penyedia_jasa->kategori = $request->kategori;
        $penyedia_jasa->bank = $request->bank;
        $penyedia_jasa->no_rek = $request->no_rek;
        if ($request->hasFile('fotoProfile')) {
            $image = $request->file('fotoProfile');
            $nama_image = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($nama_image, PATHINFO_FILENAME);
            $fileName = $fileNameWithoutExtension.'.'.$image->getClientOriginalExtension();
            $path = public_path('/images/penyedia_jasa/profile');
            $image->move($path, $fileName);
            $penyedia_jasa->profil_toko = $fileName;
        }
        if ($request->hasFile('fotoSampul')) {
            $image = $request->file('fotoSampul');
            $nama_image = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($nama_image, PATHINFO_FILENAME);
            $fileName = $fileNameWithoutExtension.'.'.$image->getClientOriginalExtension();
            $path = public_path('/images/penyedia_jasa/sampul');
            $image->move($path, $fileName);
            $penyedia_jasa->sampul_toko = $fileName;
        }
        // dd($penyedia_jasa);
        $penyedia_jasa->save();

        $user_id = auth()->user()->id_user;
        DB::update("UPDATE users SET ROLE = 1 WHERE id_user = $user_id");

        return response([
            'status' => true,
            'message' => 'Store penyedia jasa berhasil',
        ], 201);
    }

    public function store_pesan(Request $request)
    {
        $transaksi = new App\Models\Transaksi();
        $transaksi->user_id = $request->id_user;
        $transaksi->katalog_id = $request->id_katalog;
        $transaksi->tanggal = $request->tanggal;
        $transaksi->save();

        $transaksi_id = $transaksi->id_transaksi;

        $detail_transaksi = new App\Models\DetailTransaksi();
        $detail_transaksi->transaksi_id = $transaksi_id;
        $detail_transaksi->ket = $request->keterangan;
        $detail_transaksi->bukti_transfer_dp = '';
        $detail_transaksi->bukti_tf_pelunasan = '';
        $detail_transaksi->status_pembayaran = 1;
        $detail_transaksi->save();

        // dd($transaksi_id);

        return response([
            'status' => true,
            'message' => 'Store pesan berhasil',
        ], 201);
    }
}
