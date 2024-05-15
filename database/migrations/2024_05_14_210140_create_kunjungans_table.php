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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pasienId')->unsigned();
            $table->bigInteger('dokterId')->unsigned();
            $table->json('diagnosa');
            $table->json('tindakan');
            $table->timestamps();

            $table->foreign('pasienId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dokterId')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
