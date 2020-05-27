<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentBalancesView extends Migration
{
    public function up()
    {
        DB::statement(<<<'EOS'
CREATE OR REPLACE VIEW `tournament_balances` AS (
  SELECT
    transactable_id AS tournament_id,
    SUM(amount) as balance
  FROM transactions
  WHERE transactable_type = 'App\\Tournamentkings\\Entities\\Models\\Tournament'
  GROUP BY transactable_id
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
        DB::statement('DROP VIEW IF EXISTS tournament_balances');
    }
}
