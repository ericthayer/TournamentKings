<?php

namespace App\Console\Commands;

use App\GameType;
use Illuminate\Console\Command;

class AddGamesToDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:add {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Games to Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $games = [];

        if (($handle = fopen($this->argument('filename'), 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                if (! empty($data[0])) {
                    $games[] = ['name' => $data[0]];
                }
            }
        } else {
            echo 'File could not be opened.';
        }

        fclose($handle);

        GameType::insert($games);
    }
}
