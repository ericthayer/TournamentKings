<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTkSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('tk_settings')) {
            Schema::create('tk_settings', function (Blueprint $table) {
                /*
                 * This table is meant to have a single row to store app-wide settings, some of which would
                 * benefit from finding a way for the database management system to automatically factor the value into
                 * other calculations.
                 */
                $table->bigIncrements('id');
                $table->unsignedDecimal('split', 4, 4)->comment('Our fee percentage of tournament balances');
                $table->timestamps();
            });
        }

        Artisan::call('db:seed', ['--class' => 'TkSettingsTableSeeder', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tk_settings');
    }
}
