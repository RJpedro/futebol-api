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
        Schema::create('championship_matchs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('away_team_id');
            $table->unsignedBigInteger('home_team_id');
            $table->foreign('away_team_id')->references('id')->on('teams');
            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->integer('away_team_goals')->default(0);
            $table->integer('home_team_goals')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('championship_matchs');
    }
};
