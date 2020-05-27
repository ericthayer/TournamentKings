@extends('spark::layouts.app')

@section('content')
    <match-details
            :user="user"
            :match="{{$match}}"
            @if(session('tk_message_status'))
                :sessionMessage="{{ session('tk_message_status') }}"
            @endif
    >
    </match-details>
@endsection