<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('player_tournament')) {
            $sm         = Schema::getConnection()->getDoctrineSchemaManager();
            $indexFound = collect(
                $sm->listTableIndexes('player_tournament')
            )->keys()
                ->contains('player_tournament_tournament_id_player_id_unique');
            if (! $indexFound) {
                Schema::table('player_tournament', function (Blueprint $table) {
                    $table->unique(['tournament_id', 'player_id']);
                });
            }
        }

        if (! Schema::hasTable('placements')) {
            Schema::create('placements', function (Blueprint $table) {
                $table->unsignedInteger('player_tournament_id');
                $table->string('placement_type_name');
                $table->timestamps();

                $table->primary('player_tournament_id');

                $table->foreign('placement_type_name')->references('name')->on('placement_types');
                $table->foreign('player_tournament_id')->references('id')->on('player_tournament');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placements');

//        if (Schema::hasTable('player_tournament')) {
//            Schema::table('player_tournament', function (Blueprint $table) {
//                $table->dropUnique('player_tournament_tournament_id_player_id_unique');
//            });
//        }
    }
}
