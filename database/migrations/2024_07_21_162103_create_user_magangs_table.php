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
        Schema::create('user_magang', function (Blueprint $table) {
            $table->id();
            $table->string('no_telp', 20)->nullable(true);
            $table->uuid()->unique()->default(DB::raw('(UUID())'));
            $table->enum("status", ["diterima", "menunggu", "ditolak"])->default("menunggu");
            $table->dateTime('mulai_magang');
            $table->dateTime('akhir_magang');
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
        Schema::dropIfExists('user_magang');
    }
};
