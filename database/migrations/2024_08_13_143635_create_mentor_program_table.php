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
        Schema::create('mentor_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("program_id");
            $table->unsignedBigInteger("mentor_id");
            $table->timestamps();

            $table->foreign("program_id")->references("id")->on("programs")->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("mentor_id")->references("id")->on("mentors")->onUpdate("cascade")->onDelete("cascade");

            $table->unique(["program_id", "mentor_id"]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_program');
    }
};
