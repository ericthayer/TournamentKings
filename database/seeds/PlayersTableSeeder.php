<?php

use Illuminate\Database\Seeder;
use App\Tournamentkings\Entities\Models\Location;

class PlayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [];
        $table = 'players';

        DB::table($table)->delete();

        if (($h = fopen(__DIR__.DIRECTORY_SEPARATOR.'tk_player_seeds.csv', 'r')) !== false) {
            while (($data = fgetcsv($h, 1000, ',')) !== false) {
                if ($data[1] == 'userID') {
                    continue;
                }

                if (strlen($data[0])) {
                    $location = Location::where('city', strtoupper($data[4]))->where('state', strtoupper($data[5]))->first();

                    $seeds[] = [
                        'user_id'          => (int) $data[1],
                        'gamer_tag'        => $data[2],
                        'platform_type_id' => (int) $data[3],
                        'location_id'      => $location->id,
                        'created_at'       => \Carbon\Carbon::now()->toDateTimeString(),
                    ];
                }
            }
        }

        DB::table($table)->insert($seeds);

        fclose($h);

//        $sheet = Excel::selectSheets('player')->load(Config::get('tk.seeder_file_path'))->get();
//
//        // purge records
//        DB::table($table)->delete();
//        foreach($sheet as $row){
//            $id = (integer) $row->id;
//            if ( $id > 0) {
//                // this will cause error if it doesn't exist
//                $location = Location::where('city',strtoupper($row->city))->where('state',strtoupper($row->state))->first();
//                $seeds[] = array(
//                    //'id' => $id,
//                    'user_id' => (integer) $row->userid,
//                    'platform_type_id' => (integer) $row->platformtypeid,
//                    'gamer_tag' => $row->gamertag,
//                    //'city' => $row->city,
//                    //'state' => $row->state,
//                    'location_id' => $location->id,
//                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
//                );
//            }
//        }
//        // save records
//        DB::table($table)->insert($seeds);
    }
}
