<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Tournamentkings\Entities\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin role and assign it to all users

        $role = Role::create(['name' => 'Admin']);

        User::all()->each(function ($user) {
            $user->assignRole('Admin');
        });
    }
}
