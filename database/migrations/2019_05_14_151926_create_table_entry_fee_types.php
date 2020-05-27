<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tournamentkings\Entities\Models\EntryFeeType;

class CreateTableEntryFeeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('entry_fee_types')) {
            Schema::create('entry_fee_types', function (Blueprint $table) {
                $table->string('name');
                $table->string('display_name');
                $table->primary('name');
                $table->timestamps();
            });
        }

        if (EntryFeeType::all()->isEmpty()) {
            Artisan::call('db:seed', ['--class' => 'EntryFeeTypesTableSeeder', '--force' => true]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('entry_fee_types')) {
            Schema::drop('entry_fee_types');
        }
    }
}
