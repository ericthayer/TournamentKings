<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTournamentsEntryFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tournaments')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->string('entry_fee_type_name')->default('free');
                $table->decimal('entry_fee', 8, 2)->nullable();
                $table->decimal('target_pot', 8, 2)->nullable();

                $table->foreign('entry_fee_type_name')->references('name')->on('entry_fee_types');
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
        if (Schema::hasTable('tournaments')) {
            Schema::table('tournaments', function (Blueprint $table) {
                $table->dropForeign('tournaments_entry_fee_type_name_foreign');
            });
            Schema::table('tournaments', function (Blueprint $table) {
                collect([
                    'entry_fee_type_name',
                    'entry_fee',
                    'target_pot',
                ])->each(function ($column) use ($table) {
                    if (Schema::hasColumn('tournaments', $column)) {
                        $table->dropColumn($column);
                    }
                });
            });
        }
    }
}
