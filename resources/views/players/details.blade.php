@extends('spark::layouts.app')

@section('content')
    <player-details
            :user="user"
            :player="{{$player}}"
            @if(session('tk_message_status'))
                :sessionMessage="{{ session('tk_message_status') }}"
            @endif
    >
    </player-details>
@endsection