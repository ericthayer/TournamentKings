<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateBalancesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * It would be nice to find a more database-agnostic way of making a view with a Laravel migration
         */
        DB::statement(<<<'EOS'
CREATE OR REPLACE VIEW `balances` AS (
  SELECT
    transactions.user_id AS user_id,
    SUM(transactions.amount) as balance
  FROM transactions
  GROUP BY transactions.user_id
)
EOS
);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS balances');
    }
}
