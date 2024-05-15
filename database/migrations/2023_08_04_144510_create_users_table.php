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
            $table->id()->unique();
            $table->string('username', 100);
            $table->string('no_rekam_medis')->nullable();
            $table->boolean('gender');
            $table->string('kode', 50);
            $table->string('nama', 50);
            $table->string('email', 50);
            $table->string('password', 100);
            $table->string('telp', 20);
            $table->string('alamat', 100);
            $table->string('status', 10);
            $table->bigInteger('roleId')->unsigned();
            $table->date('tanggal_lahir')->nullable();
            $table->string('profile')->nullable();
            $table->string('kategoriDokter', 50)->nullable();
            $table->boolean('isPresent')->nullable();
            $table->json('jamPraktek')->nullable();
            $table->timestamps();

            $table->foreign('roleId')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
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
