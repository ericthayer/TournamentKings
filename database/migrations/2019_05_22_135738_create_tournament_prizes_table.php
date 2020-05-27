<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('tournament_prizes')) {
            Schema::create('tournament_prizes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('tournament_id');
                $table->string('placement_type_name');
                $table->decimal('amount', 8, 2);
                $table->timestamps();

                /*
                 * It would be more natural to treat these columns as a composite primary key,
                 * but Laravel strongly prefers a single one.
                 */
                //$table->primary(['tournament_id', 'placement_type_name']);
                $table->unique(['tournament_id', 'placement_type_name']);

                $table->foreign('tournament_id')
                    ->references('id')
                    ->on('tournaments')
                    ->onDelete('cascade');
                $table->foreign('placement_type_name')
                    ->references('name')
                    ->on('placement_types')
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
        Schema::dropIfExists('tournament_prizes');
    }
}
