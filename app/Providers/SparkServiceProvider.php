<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Spatie\Permission\Models\Role;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor'   => 'Tournament Kings',
        'product'  => 'Tournament Kings',
        'street'   => 'PO Box 111',
        'location' => 'Arvada, CO 80003',
        'phone'    => '555-555-5555',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = 'support@tournamentkings.com';

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [

    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
//        Spark::useStripe()->noCardUpFront()->trialDays(10);

        /*
         * Skip this method if running a command line action, such
         * as database migrations.
         */
        if (strpos(php_sapi_name(), 'cli') !== false) {
            return;
        }

        $admins = Role::where('name', 'Admin')->with('users')->first();

        if ($admins) {
            $admins = $admins->users->map(function ($item) {
                return $item->email;
            })->toArray();
        } else {
            $admins = [

            ];
        }

        Spark::developers($admins);

        Spark::freePlan('Casual', 'plan-1')
            ->features([
                'Free forever',
                '12% transaction fee\'s',
                'Withdra limited to once a month, and a minimum of $20 USD.',
                'Can participate in tournaments with a max bounty of $500 USD.',
            ]);

        Spark::plan('Rookie', env('ROOKIE_PLAN_ID', 'plan_EnqWALeOK54wAL'))
            ->price(10)
            ->features([
                '6% transaction fee\'s',
                'Withdra limited to twice a month, and a minimum of $20 USD.',
                'Can participate in tournaments with a max bounty of $1000 USD.',
                'Cancel anytime',
            ]);

        Spark::plan('Pro', env('PRO_PLAN_ID', 'plan_Enqa0SvQtbh8v2'))
            ->price(20)
            ->features([
                '3% transaction fee\'s',
                'Withdra limited to once a week, and a minimum of $20 USD.',
                'Can participate in all tournaments regardless of bounty of amount.',
                'Cancel anytime',
            ]);
    }

    public function register()
    {
        Spark::useUserModel('App\Tournamentkings\Entities\Models\User');

        Spark::useTeamModel('App\TournamentKings\Entities\Models\Team');
    }
}
