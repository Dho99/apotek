<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Produk;
use App\Models\Dokter;
use App\Models\Apoteker;
use App\Models\Kasir;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan_reseps', function (Blueprint $table) {
            $table->id()->uniqid();
            $table->string('kodePenjualan');
            $table->foreignIdFor(Apoteker::class, 'apotekerId')->default(null);
            $table->foreignIdFor(Produk::class, 'produkId')->default(null);
            $table->foreignIdFor(Dokter::class, 'dokterId')->default(null);
            $table->foreignIdFor(Kasir::class, 'kasirId')->default(null);
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
        Schema::dropIfExists('penjualan_reseps');
    }
};
