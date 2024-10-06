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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail_transaksi');
            $table->unsignedBigInteger('transaksi_id');//untuk kebutuhan user dan katalog
            $table->string('ket');
            $table->string('bukti_transfer_dp');
            $table->string('bukti_tf_pelunasan');
            $table->integer('status_pembayaran')->default('1');//1. Belum Dibayar, 2. DP, 3. Lunas
            $table->timestamps();

            $table->foreign('transaksi_id')->references('id_transaksi')->on('transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
