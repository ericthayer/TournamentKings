<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTournamentsAddPlayerDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('tournaments')) {
            /*
             * Not sure how to make this happen with DB abstraction methods.
             * The player_deposit amount is always rounded to 2 decimal places
             * thanks to the more accurate "decimal" data type.
             */
            DB::statement(<<<'EOS'
ALTER TABLE tournaments ADD COLUMN player_deposit DECIMAL(8,2) AS
  (IF(target_pot IS NOT NULL,
  target_pot / total_slots,
  NULL));
EOS
);
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
                if (Schema::hasColumn('tournaments', 'player_deposit')) {
                    $table->dropColumn('player_deposit');
                }
            });
        }
    }
}
