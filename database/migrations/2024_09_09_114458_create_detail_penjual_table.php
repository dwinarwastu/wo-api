<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_penjual', function (Blueprint $table) {
            $table->id('id_detail_penjual');
            $table->unsignedBigInteger('user_id');
            $table->string('nama_toko');
            $table->string('alamat')->nullable();
            $table->string('kategori')->nullable();
            $table->string('bank')->nullable();
            $table->string('no_rek')->unique()->nullable();
            $table->string('profil_toko')->nullable();
            $table->string('sampul_toko')->nullable();
            $table->timestamps();


            $table->foreign('user_id')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjual');
    }
};
