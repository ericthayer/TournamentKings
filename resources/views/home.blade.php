@extends('spark::layouts.app')

@section('content')
<home :user="user" :open="true" inline-template>
    <div class="container" style="max-width: 80em;">
        <!-- Application Dashboard -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if(session('verified'))
                    <div class="alert alert-success">
                        {{ __('balance.email-verified') }}
                    </div>
                @endif
                <div class="card card-default">
                    <div class="card-header">{{ __('tournaments.my-matches') }}</div>

                    <div class="card-body">
                        <div v-if="hasMatches">
                            <upcoming-matches
                                :user="user"
                                :matches="matches"
                                @clickonmatch="clickOnMatch">
                            </upcoming-matches>
                        </div>
                        <div v-else>
                            {{ __('tournaments.sorry-not-registered') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">{{ __('tournaments.upcoming-open-tournaments') }}</div>

                        <div class="card-body">
                            <div v-if="hasTournaments">
                                <upcoming-tournaments
                                    :user="user"
                                    :tournaments="tournaments"
                                    @clickontournament="clickOnTournament">
                                </upcoming-tournaments>
                            </div>
                            <div v-else>
                                {{ __('tournaments.sorry-no-open') }}
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</home>
@endsection
