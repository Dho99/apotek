<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id()->uniqid();
            $table->string('kode', 50);
            $table->json('obat_id')->default(null);
            $table->foreignId('pasien_id');
            $table->string('gejala', 255);
            $table->json('jumlah')->default(null);
            $table->foreignId('dokter_id');
            $table->text('catatan', 1000)->default(null);
            $table->boolean('isProceed')->default(0);
            $table->foreignId('apoteker_id');
            $table->boolean('isSuccess')->default(0);
            $table->boolean('isProceedByApoteker')->default(0);
            $table->json('satuan')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }

};
