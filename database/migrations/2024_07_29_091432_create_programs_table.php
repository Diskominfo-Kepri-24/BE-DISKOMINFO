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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('description');
            $table->timestamp('jadwal');
            $table->enum('tipe_program', ["online", "offline"]);
            $table->string('link_pendaftaran');
            $table->text("image");
            $table->string('category');
            $table->string("jam_program_dimulai");
            $table->text('deskripsi_sertifikat');
            $table->string('tipe_pembelajaran');
            $table->string('tipe_mentoring');
            $table->string('tipe_modul');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
