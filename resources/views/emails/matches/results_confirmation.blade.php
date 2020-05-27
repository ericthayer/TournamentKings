@component('mail::message')
# Hello!

Please click on the button below to log in and confirm the newly posted match results for Tournament "{{ $match->tournament->name }}".

Round: {{ $match->round }}<br>
Match: {{ $match->number }}<br>
Winner: {{ $match->getWinnerGamerTag() }}<br>
Score: {{ $match->getWinnerScore() }}<br>
Loser: {{ $match->getLoserGamerTag() }}<br>
Score: {{ $match->getLoserScore() }}<br>
Posted: {{ date('m/d/Y g:i A', strtotime($match->result_posted_at)) }}<br>

@component('mail::button', ['url' => config('app.url') . '/matches/' . $match->id . '/confirmresults']) Confirm Results @endcomponent

Regards,<br>{{ config('app.name') }}
@endcomponent
