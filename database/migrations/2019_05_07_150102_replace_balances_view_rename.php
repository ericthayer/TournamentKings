<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class ReplaceBalancesViewRename extends Migration
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
        DB::statement('DROP VIEW IF EXISTS balances;');
        DB::statement(<<<'EOS'
CREATE OR REPLACE VIEW `user_balances` AS (
  SELECT
    transactable_id AS user_id,
    SUM(amount) AS balance
  FROM transactions
  WHERE transactable_type = 'App\\Tournamentkings\\Entities\\Models\\User'
  GROUP BY transactable_id
);
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
        DB::statement('DROP VIEW IF EXISTS user_balances');
        (new CreateBalancesView)->up();
    }
}
