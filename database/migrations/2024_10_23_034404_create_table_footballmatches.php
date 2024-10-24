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
            $table->dateTime('match_date');
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->unsignedSmallInteger('home_score');
            $table->unsignedSmallInteger('away_score');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('home_team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('away_team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_footballmatches');
    }
};