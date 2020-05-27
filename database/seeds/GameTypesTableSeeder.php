<?php

use Illuminate\Database\Seeder;

class GameTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [];
        $table = 'game_types';

        DB::table($table)->delete();

        if (($h = fopen(__DIR__.DIRECTORY_SEPARATOR.'tk_game_type_seeds.csv', 'r')) !== false) {
            while (($data = fgetcsv($h, 1000, ',')) !== false) {
                if ($data[1] === 'description') {
                    continue;
                }

                if (strlen($data[0])) {
                    $seeds[] = [
                        'name' => $data[1],
                    ];
                }
            }
        }

        DB::table($table)->insert($seeds);

        fclose($h);
    }
}
