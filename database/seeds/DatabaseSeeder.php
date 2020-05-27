<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->command->info('users table seeded!');

        $this->call(RolesSeeder::class);
        $this->command->info('users are all admins!');

        $this->call(PlatformTypesTableSeeder::class);
        $this->command->info('platform types table seeded!');

        $this->call(LocationsTableSeeder::class);
        $this->command->info('locations table seeded!');

        $this->call(PlayersTableSeeder::class);
        $this->command->info('players table seeded!');

        $this->call(GameTypesTableSeeder::class);
        $this->command->info('game types table seeded!');

        $this->call(TransactionTypeSeeder::class);
        $this->command->info('transaction types created!');

        $this->call(EntryFeeTypesTableSeeder::class);
        $this->command->info('entry fee types created!');

        $this->call(PlacementTypesTableSeeder::class);
        $this->command->info('placement types created!');

        $this->call(TkSettingsTableSeeder::class);
        $this->command->info('TK settings created!');
    }
}
