<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Supplier;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id()->unqiue();
            $table->string('kode', 50);
            $table->string('namaProduk', 75);
            $table->json('golongan_id')->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('posisiBarang');
            $table->string('satuan', 50);
            $table->string('batch');
            $table->integer('hargaBeli');
            $table->integer('hargaJual');
            $table->integer('stok')->default(0);
            $table->unsignedBigInteger('supplier_id');
            $table->string('image')->nullable();
            $table->date('expDate');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('cascade');
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
