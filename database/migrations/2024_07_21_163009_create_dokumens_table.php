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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user_magang');
            $table->string('cv');
            $table->string('transkip_nilai');
            $table->string('surat_rekomendasi');
            $table->string('ktp');
            $table->string('sertifikat')->nullable(true)->default(null);
            $table->string('dokumen_tambahan')->nullable(true)->default(null);
            $table->timestamps();

            $table->foreign('id_user_magang')->references('id')->on('user_magang')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
