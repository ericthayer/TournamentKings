<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacementTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('placement_types')) {
            Schema::create('placement_types', function (Blueprint $table) {
                $table->string('name');
                $table->bigIncrements('place')->comment('An auto-incrementing number with which to rank placements among each other');
                $table->unsignedDecimal('split', 4, 4);
                $table->timestamps();

                $table->unique('place');
                $table->dropPrimary('place');

                $table->primary('name');
            });
        }

        Artisan::call('db:seed', ['--class' => 'PlacementTypesTableSeeder', '--force' => true]);

        DB::statement("ALTER TABLE placement_types COMMENT = '".<<<'EOS'
Use this table to manage the placements that are possible in a game, such as gold, silver and bronze.
Set the "split" to a decimal value representing the percentage of the tournament balance to award for the placement after our fee is taken out.
EOS
."'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('placement_types');
    }
}
