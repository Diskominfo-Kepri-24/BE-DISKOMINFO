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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_berita");
            $table->unsignedBigInteger("id_user");
            $table->string("tanggal");
            $table->string("isi_komentar");
            $table->timestamps();

            $table->foreign("id_berita")->references("id")->on("news")->onUpdate("cascade")->onDelete("cascade");           
            $table->foreign("id_user")->references("id")->on("users")->onUpdate("cascade")->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
