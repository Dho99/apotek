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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->uniqid();
            $table->string('username', 100);
            $table->string('kode', 50);
            $table->string('nama', 50);
            $table->string('email', 50);
            $table->string('password', 100);
            $table->string('telp', 20);
            $table->string('alamat', 100);
            $table->string('status', 10);
            $table->integer('level')->default(null);
            $table->string('profile')->default(null);
            $table->string('kategoriDokter', 50)->default(null);
            $table->boolean('isPresent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
