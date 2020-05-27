<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use App\Nova\Filters\TransactionType;
use App\Tournamentkings\Entities\Models\Transaction;

class Transactions extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Transaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            ID::make()->sortable(),
            Number::make('Transaction Id', function () {
                return $this->transaction_id;
            })->sortable()->readonly(true),
            Text::make('Type', function () {
                return $this->transaction_type_name;
            })->sortable(),
            Text::make('For', function () {
                $model = $this->transactable_type;
                $id    = $this->transactable_id;

                $baseclass = class_basename($this->transactable_type);
                $info       = "$baseclass: $id";

                $record = $model::find($id);
                if ($record) {
                    $name = $model::find($id)->name;
                    $info = "{$baseclass}: $name";
                }

                return $info;
            }),
            Currency::make('Amount', function () {
                return number_format(abs($this->amount), 2);
            })->sortable(),
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
        return [
            new TransactionType,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
