<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWithdrawalsTable extends Migration
{
    const TABLE = 'user_withdrawals';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable(self::TABLE)) {
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('user_id');
                $table->unsignedDecimal('amount');
                $table->string('withdrawal_type_name');
                $table->string('email', 10)->nullable();
                $table->string('phone_number', 18)->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('withdrawal_type_name')->references('name')->on('withdrawal_types');
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
        Schema::dropIfExists(self::TABLE);
    }
}
