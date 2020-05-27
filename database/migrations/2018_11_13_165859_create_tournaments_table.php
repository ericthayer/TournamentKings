<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('total_slots');
            $table->unsignedInteger('winner_player_id')->nullable();
            $table->foreign('winner_player_id')->references('id')->on('players');
            $table->unsignedInteger('created_by_player_id');
            $table->foreign('created_by_player_id')->references('id')->on('players');
            $table->unsignedInteger('game_type_id');
            $table->foreign('game_type_id')->references('id')->on('game_types');
            $table->string('tournament_type');
            $table->string('password')->nullable();
            $table->dateTime('start_datetime');
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
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropForeign('tournaments_winner_player_id_foreign');
            $table->dropForeign('tournaments_created_by_player_id_foreign');
            $table->dropForeign('tournaments_game_type_id_foreign');
        });
        Schema::dropIfExists('tournaments');
    }
}
