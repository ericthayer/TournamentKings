<?php

namespace App\Http\Controllers;

use Parsedown;

class PrivacyController extends Controller
{
    public function show()
    {
        $privacyFile = file_exists(base_path('terms.'.app()->getLocale().'.md'))
            ? base_path('privacy.'.app()->getLocale().'.md')
            : base_path('privacy.md');

        return view('privacy', [
            'privacy' => (new Parsedown)->text(file_get_contents($privacyFile)),
        ]);
    }
}
