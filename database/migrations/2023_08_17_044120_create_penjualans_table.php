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
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('patientId');
            $table->json('jumlah');
            $table->boolean('isGerus');
            $table->integer('subtotal')->default(null);
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('patientId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

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
