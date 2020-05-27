<?php

use Illuminate\Database\Seeder;

class PlatformTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [];
        $table = 'platform_types';

        DB::table($table)->delete();

        if (($h = fopen(__DIR__.DIRECTORY_SEPARATOR.'tk_platform_type_seeds.csv', 'r')) !== false) {
            while (($data = fgetcsv($h, 1000, ',')) !== false) {
                if (strlen($data[0])) {
                    if ($data[1] == 'description') {
                        continue;
                    }

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
