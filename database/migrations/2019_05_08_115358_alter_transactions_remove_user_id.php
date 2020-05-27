<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tournamentkings\Entities\Models\Transaction;

class AlterTransactionsRemoveUserId extends Migration
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
                $table->dropForeign('transactions_user_id_foreign');
                $table->dropColumn('user_id');
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
                $table->unsignedInteger('user_id');
            });

            Schema::table('transactions', function (Blueprint $table) {
                Transaction::where('user_id', 'IS NOT NULL')->update(['user_id' => 1]);
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }
}
