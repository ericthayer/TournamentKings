<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Naif\NovaSms\NovaSms;
use Laravel\Nova\Cards\Help;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        $admins = Role::where('name', 'Admin')->with('users')->first();

        if ($admins) {
            $admins = $admins->users->map(function ($item) {
                return $item->email;
            })->toArray();
        } else {
            $admins = [

            ];
        }

        Gate::define('viewNova', function ($user) use ($admins) {
            return in_array($user->email, $admins);
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
            \Themsaid\CashierTool\CashierTool::make(),
            \Tightenco\NovaStripe\NovaStripe::make(),
            NovaSms::make(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
