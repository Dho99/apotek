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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('kodePenjualan');
            $table->json('produk_id');
            $table->unsignedBigInteger('apoteker_id');
            $table->unsignedBigInteger('dokter_id');
            $table->string('kategoriPenjualan');
            $table->json('harga');
            $table->integer('dsc');
            $table->json('jumlah');
            $table->integer('subtotal')->default(null);
            $table->timestamps();

            $table->foreign('apoteker_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dokter_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
