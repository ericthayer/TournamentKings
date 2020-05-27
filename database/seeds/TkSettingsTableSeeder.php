<?php

use Illuminate\Database\Seeder;
use App\Tournamentkings\Entities\Models\TkSetting;

class TkSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (TkSetting::all()->isEmpty()) {
            TkSetting::create([
                'split' => .1,
            ]);
        }
    }
}
