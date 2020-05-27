<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Mail\LeadFormCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\LeadFormRequest;
use App\Tournamentkings\Entities\Models\Lead;
use App\TournamentKings\Entities\Models\CodeRedirect;

class WelcomeController extends Controller
{
    protected $redirectTo = '/home';

    public function index()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo);
        } else {
            return view('landing.landing');
        }
    }

    public function show(Request $request)
    {
        $code = $request->input('code');

        return view('lead-registration')->with('code', $code);
    }

    public function store(LeadFormRequest $request)
    {
        $data     = $request->all();
        $redirect = '/thankyou';
        unset($data['_token']);
        unset($data['terms']);

        $data['unique_id'] = Uuid::uuid1()->toString();

        $lead = Lead::create($data);

        Mail::to($lead->Email)->send(new LeadFormCompletion($lead));

        $code = strtolower($request->input('Code'));

        $codes = CodeRedirect::all('code')->map(function ($item) {
            return strtolower($item->code);
        })->toArray();

        if (in_array($code, $codes)) {
            $codeUrl  = CodeRedirect::where('code', $code)->first('url');
            $redirect = $codeUrl->url;
        }

        return redirect($redirect);
    }

    public function thanks()
    {
        return view('landing.thankyou');
    }

    public function discordBot()
    {
        return view('landing.tourneybot');
    }
}
