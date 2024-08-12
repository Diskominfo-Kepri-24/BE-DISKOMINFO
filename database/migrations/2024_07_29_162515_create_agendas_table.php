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
    Schema::create('agendas', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('slug')->unique();
        $table->enum("status", ["Sudah Selesai", "Belum Selesai"]);
        $table->dateTime("tanggal_mulai");
        $table->dateTime("tanggal_selesai");
        $table->enum("tipe_acara", ["online", "offline"]);
        $table->string("isi_agenda");
        $table->string("foto");
        $table->dateTime("tgl_event_mulai");
        $table->dateTime("tgl_event_akhir");
        $table->string("tanggal_event_mulai");
        $table->string("tanggal_event_akhir");
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
