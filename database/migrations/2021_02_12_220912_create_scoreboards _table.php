<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoreboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scoreboards', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['official', 'bet'])->nullable();
            $table->foreignId('game_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->integer('team_home_scoreboard')->nullable();
            $table->integer('team_guest_scoreboard')->nullable();

            $table->float('score')->default(0);
            $table->longText('report')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scoreboards');
    }
}
