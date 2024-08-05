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
        Schema::create('magang', function (Blueprint $table) {
            $table->id();
            $table->string('jurusan', 20)->nullable(true);
            $table->string('no_induk');
            $table->string("jenjang");
            $table->string("instansi");
            $table->string('surat_magang');
            $table->dateTime('mulai_magang');
            $table->dateTime('akhir_magang');
            $table->string('alasan_magang');
            $table->string('motivasi_magang');
            $table->enum("status", ["diterima", "menunggu", "ditolak"])->default("menunggu");
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magang');
    }
};
