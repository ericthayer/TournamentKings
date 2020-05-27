<?php

namespace Tests\Browser\Auth\Tournaments;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Tournamentkings\Entities\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCanSeeIndex()
    {
        $this->seed();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::role('Admin')->first())
                ->visit(route('tournaments.index'))
                ->assertSee('Create Tournament');
        });
    }
}
