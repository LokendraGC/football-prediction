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
        Schema::create('football_matches', function (Blueprint $table) {
            $table->id();
            $table->string('match_name');
            $table->dateTime('match_date');
            $table->string('team_a_name');
            $table->string('team_b_name');
            $table->integer('team_a_score')->nullable();
            $table->integer('team_b_score')->nullable();
            $table->string('match_with');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_matches');
    }
};
