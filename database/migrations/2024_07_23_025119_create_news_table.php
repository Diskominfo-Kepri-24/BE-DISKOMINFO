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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_user");
            $table->dateTime("tanggal");
            $table->string('slug')->unique();
            $table->string("judul");
            $table->string("gambar");
            $table->text("isi_berita");
            $table->string("kategori");
            $table->timestamps();

            $table->foreign("id_user")->references("id")->on("users")->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
