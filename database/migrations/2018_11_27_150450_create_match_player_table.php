<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players');
            $table->unsignedInteger('match_id');
            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->integer('order');
            $table->float('score', 20, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('match_player', function (Blueprint $table) {
            $table->dropForeign('match_player_player_id_foreign');
            $table->dropForeign('match_player_match_id_foreign');
        });
        Schema::dropIfExists('match_player');
    }
}
