<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tournamentkings\Entities\Models\Transaction;

class AlterPlayerTransactionsNullableTransId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('transaction_id')->nullable(true)->change();
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
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                Transaction::where('transaction_id', null)->update(['transaction_id' => 0]);
                $table->string('transaction_id')->nullable(false)->change();
            });
        }
    }
}
