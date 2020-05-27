<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class TransactionType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('transaction_type_name', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Tournament Buy-In' => 'internal_tournament-buy-in',
            'Tournament Payout' => 'internal_tournament_payout',
            'User Refund'       => 'internal_user_refund',
            'User Withdrawal'   => 'internal_user_withdrawal',
            'TK Payout'         => 'internal_tk_payout',
            'Square'            => 'square',
        ];
    }
}
