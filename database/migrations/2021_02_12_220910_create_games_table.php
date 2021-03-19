<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('status')->nullable();
            $table->string('stage')->nullable();

            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('team_home_id')->constrained('teams');
            $table->foreignId('team_guest_id')->constrained('teams');
            $table->foreignId('stadium_id')->constrained();
            $table->foreignId('competition_id')->constrained();

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
        Schema::dropIfExists('games');
    }
}
