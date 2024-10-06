<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert(
            [
                [
                    'id_user' => '111',
                    'nama' => 'Savira Ciliania',
                    'no_telp' => '087615243654',
                    'email' => 'savirasilla@gmail.com',
                    'username' => 'saviraci75',
                    'password' => Hash::make('1'),
                    'foto' => '-.png',
                    'role' => '1',
                    'alamat' => 'Kab. Kediri',
                ],
                [
                    'id_user' => '999',
                    'nama' => 'Savira Ciliania',
                    'no_telp' => '087605243654',
                    'email' => 'satirasilla@gmail.com',
                    'username' => 'uu1',
                    'password' => Hash::make('1'),
                    'foto' => '-.png',
                    'role' => '1',
                    'alamat' => 'Kab. Kediri',
                ]
            ]
        );

        DB::table('detail_penjual')->insert(
            [
                [
                    'id_detail_penjual' => '2',
                    'user_id' => '999',
                    'nama_toko' => 'Okelah Wedding',
                    'kategori' => 'Dekorasi',
                    'bank' => 'BRI',
                    'no_rek' => '7289108273391722',
                    'profil_toko' => '-.png',
                    'sampul_toko' => '-.png',
                ],
                [
                    'id_detail_penjual' => '222',
                    'user_id' => '111',
                    'nama_toko' => 'Savira Wedding',
                    'kategori' => 'Make Up Artis',
                    'bank' => 'BRI',
                    'no_rek' => '7289108273391726',
                    'profil_toko' => '-.png',
                    'sampul_toko' => '-.png',
                ]
            ]
        );

        DB::table('katalog')->insert(
            [
                [
                    'id_katalog' => '333',
                    'detail_penjual_id' => '222',
                    'judul' => 'Paket Make Up Arabian Look',
                    'deskripsi' => 'Arabian look identic dengan riasan di bagian mata dengan ciri bulu mata yang tebal dan menggunakan smokey eyes. ...',
                    'metode_bayar' => '1',
                ]
            ]
        );

        DB::table('detail_katalog')->insert(
            [
                [
                    'id_detail_katalog' => '777',
                    'judul_variasi' => 'merah',
                    'katalog_id' => '333',
                    'harga' => '2000000',
                    'gambar' => '1.jpg',
                ],
                [
                    'id_detail_katalog' => '888',
                    'judul_variasi' => 'biru',
                    'katalog_id' => '333',
                    'harga' => '3500000',
                    'gambar' => '2.jpg',
                ],
                [
                    'id_detail_katalog' => '999',
                    'judul_variasi' => 'kuning',
                    'katalog_id' => '333',
                    'harga' => '3000000',
                    'gambar' => '3.jpg',
                ],
            ]
        );

        DB::table('kategori')->insert(
            [
                [
                    'judul_kategori' => 'Make Up Artist',
                    'gambar_kategori' => 'Beautician.png'
                ],
                [
                    'judul_kategori' => 'Dekorasi',
                    'gambar_kategori' => 'Beautiful Wedding Ribbon.png'
                ],
                [
                    'judul_kategori' => 'Sound Systems',
                    'gambar_kategori' => 'Subwoofer.png'
                ],
                [
                    'judul_kategori' => 'Cathering',
                    'gambar_kategori' => 'Buffet Breakfast.png'
                ],
                [
                    'judul_kategori' => 'Wedding Organizer',
                    'gambar_kategori' => 'Tasklist.png'
                ],
                [
                    'judul_kategori' => 'Photography',
                    'gambar_kategori' => 'SLR Camera.png'
                ],
                [
                    'judul_kategori' => 'Undangan',
                    'gambar_kategori' => 'Letter.png'
                ],
                [
                    'judul_kategori' => 'Souvenir',
                    'gambar_kategori' => 'Favorite Package.png'
                ]
            ]
        );
    }
}
