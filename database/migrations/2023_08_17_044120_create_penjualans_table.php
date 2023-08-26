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
            $table->id()->uniqid();
            $table->string('kodePenjualan');
            $table->foreignIdFor(App\Models\Produk::class);
            $table->foreignId('apoteker_id');
            $table->foreignId('dokter_id');
            $table->foreignId('pasien_id');
            $table->string('kategoriPenjualan');
            $table->integer('harga')->default(null);
            $table->integer('jumlah')->default(null);
            $table->integer('subtotal')->default(null);
            $table->timestamps();
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
