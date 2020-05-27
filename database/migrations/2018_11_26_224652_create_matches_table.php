<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tournament_id');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->integer('round')->nullable();
            $table->integer('number')->nullable();
            $table->unsignedInteger('winner_player_id')->nullable();
            $table->foreign('winner_player_id')->references('id')->on('players');
            $table->dateTime('result_posted_at')->nullable();
            $table->dateTime('result_confirmed_at')->nullable();
            $table->unsignedInteger('result_posted_by')->nullable();
            $table->foreign('result_posted_by')->references('id')->on('players');
            $table->unsignedInteger('result_confirmed_by')->nullable();
            $table->foreign('result_confirmed_by')->references('id')->on('players');
            $table->string('result_screen')->nullable();
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
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign('matches_tournament_id_foreign');
            $table->dropForeign('matches_winner_player_id_foreign');
            $table->dropForeign('matches_result_posted_by_foreign');
            $table->dropForeign('matches_result_confirmed_by_foreign');
        });
        Schema::dropIfExists('matches');
    }
}
