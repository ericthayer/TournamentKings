<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds   = [];
        $counter = 0;
        $table   = 'locations';

        // purge records
        DB::table($table)->delete();

        if (($h = fopen(__DIR__.DIRECTORY_SEPARATOR.'locations.csv', 'r')) !== false) {
            while (($data = fgetcsv($h, 1000, ',')) !== false) {
                if ($data[0] === 'city') {
                    continue;
                }

                if (strlen($data[0]) && ($data[1] != 'AZ') && ($data[1] != 'IA') && ($data[1] != 'LA')) {
                    $seeds[] = [
                        'city'  => $data[0],
                        'state' => $data[1],
                    ];

                    $counter++;

                    if ($counter > 500) {
                        // save 500 records at a time
                        DB::table($table)->insert($seeds);
                        $seeds   = [];
                        $counter = 0;
                    }
                }
            }
        }

        if ($counter) {
            DB::table($table)->insert($seeds);
        }

        fclose($h);
    }
}
