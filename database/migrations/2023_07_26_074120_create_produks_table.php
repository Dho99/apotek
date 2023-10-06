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
            $table->id()->uniqid();
            $table->string('kode', 50);
            $table->string('namaProduk', 50);
            $table->json('golongan_id')->nullable();
            $table->string('satuan', 50);
            $table->bigInteger('harga');
            $table->integer('stok')->default(0);
            $table->foreignIdFor(Supplier::class);
            $table->string('image')->nullable();
            $table->date('expDate');
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
