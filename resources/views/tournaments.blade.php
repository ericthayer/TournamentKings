@extends('spark::layouts.app')

@section('content')
<tournaments :user="user" inline-template>
    <div class="container">
        <!-- Main Tournaments Page -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">{{ __('tournaments.tournaments') }}</div>
                    @if (session('tk_message_status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('tk_message_status') }}
                        </div>
                    @endif
                    <div class="card-body">
                        <p><a class="btn btn-primary" href="{{ route('tournaments.create', null, false) }}" role="button">{{ __('tournaments.create') }}</a></p>
                        <div v-if="hasTournaments">
                            <upcoming-tournaments
                                :user="user"
                                :tournaments="tournaments"
                                @clickontournament="clickOnTournament">
                            </upcoming-tournaments>
                        </div>
                        <div v-else>
                            {{ __('tournaments.sorry-no-registered') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</tournaments>
@endsection
