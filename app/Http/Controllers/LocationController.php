<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournamentkings\Entities\Models\Location;

class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        $term = strtolower($request->input('query', ''));
        $data = Location::selectRaw("id, CONCAT_WS(', ', city, state) AS label")->whereRaw("LOWER(CONCAT_WS(', ', city, state)) LIKE ?", [$term.'%'])->orderBy('label')->limit(6)->get();

        return response()->json($data);
    }
}
