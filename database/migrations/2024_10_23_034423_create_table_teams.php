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

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('stadium');
            $table->softDeletes(); // Adds 'deleted_at' for soft deletes
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_teams');
    }
};
