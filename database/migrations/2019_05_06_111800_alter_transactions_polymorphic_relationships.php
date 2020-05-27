<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionsPolymorphicRelationships extends Migration
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
                $table->unsignedInteger('transactable_id');
                $table->string('transactable_type');

                $table->string('transaction_type_name');

                $table->dropColumn('payment_processor');
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
                $table->string('payment_processor');
                $table->dropColumn(['transactable_id', 'transactable_type', 'transaction_type_name']);
            });
        }
    }
}
