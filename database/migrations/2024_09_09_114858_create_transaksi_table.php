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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('user_id');//nama
            $table->unsignedBigInteger('katalog_id');//katalog
            $table->date('tanggal');
            $table->integer('status')->default('1');//1. Pengajuan, 2. Diterima, 3. Ditolak
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users');
            $table->foreign('katalog_id')->references('id_katalog')->on('katalog');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
