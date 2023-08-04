<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Apoteker;
use App\Models\Supplier;
use App\Models\Produk;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('noFaktur', 50);
            $table->foreignIdFor(Apoteker::class, 'apotekerId')->default(null);
            $table->foreignIdFor(Supplier::class, 'supplierId')->default(null);
            $table->foreignIdFor(Produk::class, 'produkId')->default(null);
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
        Schema::dropIfExists('pembelians');
    }
};
