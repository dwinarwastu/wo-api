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
        Schema::create('katalog', function (Blueprint $table) {
            $table->id('id_katalog');
            $table->unsignedBigInteger('detail_penjual_id');
            $table->string('judul');
            $table->string('deskripsi')->nullable();
            $table->integer('metode_bayar')->nullable();//1. TF 50%, 2. TF 100%, 3. DST
            $table->timestamps();

            $table->foreign('detail_penjual_id')->references('id_detail_penjual')->on('detail_penjual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog');
    }
};
