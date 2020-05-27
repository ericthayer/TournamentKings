<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [];
        $table = 'users';

        DB::table($table)->delete();

        if (($h = fopen(__DIR__.DIRECTORY_SEPARATOR.'tk_user_seeds.csv', 'r')) !== false) {
            while (($data = fgetcsv($h, 1000, ',')) !== false) {
                if ($data[1] == 'name') {
                    continue;
                }
                if (strlen($data[0])) {
                    $seeds[] = [
                        'name'              => $data[1],
                        'email'             => $data[2],
                        'password'          => Hash::make($data[3]),
                        'created_at'        => \Carbon\Carbon::now()->toDateTimeString(),
                        'email_verified_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    ];
                }
            }
        }

        DB::table($table)->insert($seeds);

        fclose($h);
    }
}
