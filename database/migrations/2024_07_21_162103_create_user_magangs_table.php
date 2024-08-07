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
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('no_induk');
            $table->string('no_hp');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('jurusan');
            $table->string("jenjang");
            $table->string("instansi");
            $table->string('surat_magang');
            $table->date('mulai_magang');
            $table->date('akhir_magang');
            $table->string('alasan_magang');
            $table->enum("status", ["terima", "pending", "tolak"])->default("pending");
            $table->unsignedBigInteger('id_user')->unique()->nullable();
            $table->timestamps();

            // $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
