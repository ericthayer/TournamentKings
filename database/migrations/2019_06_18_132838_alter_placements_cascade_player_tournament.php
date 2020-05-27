<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlacementsCascadePlayerTournament extends Migration
{
    const TABLE = 'placements';
    const KEY   = 'placements_player_tournament_id_foreign';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE)) {
            $sm         = Schema::getConnection()->getDoctrineSchemaManager();
            $indexFound = collect(
                $sm->listTableForeignKeys(self::TABLE)
            )->contains(function ($key) {
                return $key->getName() === self::KEY;
            });
            if ($indexFound) {
                Schema::table(self::TABLE, function (Blueprint $table) {
                    $table->dropForeign(self::KEY);
                });
            }

            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->foreign('player_tournament_id')
                    ->references('id')
                    ->on('player_tournament')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        if (Schema::hasTable(self::TABLE)) {
            $sm         = Schema::getConnection()->getDoctrineSchemaManager();
            $indexFound = collect(
                $sm->listTableForeignKeys(self::TABLE)
            )->contains(function ($key) {
                return $key->getName() === self::KEY;
            });
            if ($indexFound) {
                Schema::table(self::TABLE, function (Blueprint $table) {
                    $table->dropForeign(self::KEY);
                });
            }

            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->foreign('player_tournament_id')
                    ->references('id')
                    ->on('player_tournament');
            });
        }
    }
}
