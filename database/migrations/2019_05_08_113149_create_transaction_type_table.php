<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tournamentkings\Entities\Models\Transaction;
use App\TournamentKings\Entities\Models\TransactionType;

class CreateTransactionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('transaction_types')) {
            Schema::create('transaction_types', function (Blueprint $table) {
                $table->string('name');
                $table->primary('name');
                $table->timestamps();
            });
        }

        Artisan::call('db:seed', ['--class' => 'TransactionTypeSeeder', '--force' => true]);

        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                Transaction::where('transaction_type_name', '')
                    ->update(['transaction_type_name' => TransactionType::first()->name]);
                $table->foreign('transaction_type_name')->references('name')->on('transaction_types');
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
                $table->dropForeign('transactions_transaction_type_name_foreign');
            });
        }

        Schema::dropIfExists('transaction_types');
    }
}
