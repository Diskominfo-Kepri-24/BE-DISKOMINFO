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
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_user_magang");
            $table->date("tanggal");
            $table->string("hari");
            $table->time("jam_masuk")->nullable();
            $table->time("jam_pulang")->nullable();
            $table->enum("status", ["dikonfirmasi", "menunggu", "ditolak"])->default("menunggu");
            $table->timestamps();

            $table->foreign("id_user_magang")->references("id")->on("magang")->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
