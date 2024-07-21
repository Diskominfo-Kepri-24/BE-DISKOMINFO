<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_magang_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('universitas');
            $table->uuid()->unique()->default(DB::raw('(UUID())'));
            $table->string('jurusan');
            $table->string('nim');
            $table->string('tahun_angkatan')->nullable(true);
            $table->unsignedBigInteger('id_user_magang');
            $table->timestamps();

            $table->foreign('id_user_magang')->references('id')->on('user_magang')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_magang_mahasiswa');
    }
};
