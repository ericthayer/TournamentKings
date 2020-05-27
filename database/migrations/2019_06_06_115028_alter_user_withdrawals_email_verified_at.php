<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserWithdrawalsEmailVerifiedAt extends Migration
{
    const TABLE  = 'user_withdrawals';
    const COLUMN = 'email_verified_at';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->timestamp(self::COLUMN)->nullable();
                $table->string('email', 60)->change();
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
        if (Schema::hasTable(self::TABLE) && Schema::hasColumn(self::TABLE, self::COLUMN)) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->dropColumn(self::COLUMN);
                //$table->string('email', 10)->change();
            });
        }
    }
}
