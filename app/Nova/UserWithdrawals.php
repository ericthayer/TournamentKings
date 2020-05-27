<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Actions\RefundUser;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use App\Tournamentkings\Entities\Models\User;
use App\TournamentKings\Entities\Models\UserWithdrawal;

class UserWithdrawals extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = UserWithdrawal::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'created_at';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'created_at',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            DateTime::make('Created At')->sortable(),
            Text::make('Name', function () {
                return User::find($this->user_id)->name;
            })->sortable(),
            Text::make('Email', function () {
                return $this->email;
            })->sortable(),
            Currency::make('Amount')->sortable(),
            Text::make('Payment Type', function () {
                return ucwords($this->withdrawal_type_name);
            })->sortable(),
            Text::make('Phone Number')->sortable(),
            Boolean::make('Verified', function () {
                return (bool) $this->email_verified_at;
            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [new RefundUser];
    }

    public static function label()
    {
        return 'User Withdrawals';
    }
}
