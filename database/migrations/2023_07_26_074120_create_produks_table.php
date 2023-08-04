<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Supplier;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id()->uniquid();
            $table->string('kode', 10);
            $table->string('namaProduk', 50);
            $table->string('kategori', 50);
            $table->string('satuan', 50);
            $table->string('stok', 50);
            $table->integer('jumlah')->default(null);
            $table->foreignIdFor(Supplier::class, 'supplierId');
            $table->integer('hargaBeli')->default(null);
            $table->integer('hargaJual')->default(null);
            $table->integer('subtotal')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
