<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournamentkings\Entities\Models\PlatformType;

class PlatformTypeController extends Controller
{
    public function index(Request $request)
    {
        return PlatformType::all()->toArray();
    }
}
