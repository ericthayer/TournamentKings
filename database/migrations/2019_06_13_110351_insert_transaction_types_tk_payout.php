<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\TournamentKings\Entities\Models\TransactionType;

class InsertTransactionTypesTkPayout extends Migration
{
    const NEW_NAME = 'internal_tk_payout';
    const TABLE    = 'transaction_types';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE)) {
            $record = TransactionType::find(self::NEW_NAME);
            if (! $record) {
                TransactionType::create(['name' => self::NEW_NAME]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
