@extends('spark::layouts.app')

@section('content')
<tournamentdetail :user="user" :tournament="{{ $tournament }}" :matches="{{ $tournament->matches }}" inline-template>
    <div class="container tournament-details-container">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="card">
                    <div class="card-header"><h1>{{ $tournament->name}}</h1></div>

                    <div class="card-body">

                        @if (session('tk_message_status'))
                            <div class="alert alert-success" role="alert">
                                {!! session('tk_message_status') !!}
                            </div>
                        @endif

                        @if($errors->isNotEmpty())
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <h3>{{ __('tournaments.details') }}:</h3>

                        <h6>{{ __('tournaments.game') }}: <span class="text-muted">{{ $tournament->gameType->name }}</span> </h6>
                        <h6>{{ __('tournaments.type') }}: <span class="text-muted">{{ $tournament_types[$tournament->tournament_type] }}</span> </h6>
                        <h6>{{ __('tournaments.notes') }}: <span class="text-muted">{{ $tournament->description}}</span> </h6>
                        <h6>{{ __('tournaments.start-date-time') }}: <span class="text-muted">{{ date('m/d/Y g:i A', strtotime($tournament->start_datetime)) }}</span> </h6>
                        <h6>{{ __('tournaments.winner') }}: <span class="text-muted">{{ $tournament->winner_gamer_tag }}</span> </h6>
                        <h6>{{ __('tournaments.admin') }}: <span class="text-muted">{{ $tournament->createdByPlayer->gamer_tag }}</span> </h6>
                        <p>
                            @if ($tournament->mayEdit())
                                <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}/edit" role="button">{{ __('tournaments.edit') }}</a>
                                &nbsp;&nbsp;
                            @endif
                            @if ($tournament->mayDelete())
                                &nbsp;&nbsp;
                                <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}/delete" role="button">{{ __('tournaments.delete') }}</a>
                            @endif
                            </p>
                        <br>

                        @if(!$tournament->entryFeeType->is_free)
                            @if($balance)
                                    <h3>{{ __('balance.balance') }}</h3>
                                    <h6>{{ $formattedBalance }}</h6>

                                    <h3>{{ __('tournaments.prizes') }}</h3>
                                    <ul>
                                        @foreach($tournament->prizes as $prize)
                                            <li>{{ $prize->placementType->display_name }} - {{$prize->display_amount}}</li>
                                        @endforeach
                                    </ul>
                            @endif

                            @if($tournament->target_pot)
                                <h3>Target Pot</h3>
                                <h6>{{ $tournament->display_target_pot }}</h6>
                            @endif
                        @endif
                        @if($placement)
                            <h4>{{ __('tournaments.placement-congrats', ['place' => $placement->placementType->display_name]) }}</h4>
                        @endif
                        <h3>{{ __('tournaments.players') }}:</h3>
                        <h6>{{ __('tournaments.available-slots') }}: <span class="text-muted">{{ $tournament->available_slots }} of {{ $tournament->total_slots }}</span> </h6>
                        <h6>Registered players: <span class="text-muted">{{ $tournament->getRegisteredPlayerList() }}</span> </h6>
                        <p>
                            @if ($tournament->mayRegister())
                                <a
                                    class="btn btn-primary"
                                    href="{{ route('register.confirm', ['tournament_id' => $tournament->id], false) }}"
                                    role="button"
                                >
                                    {{ __('auth.register') }}
                                </a>
                            @endif
                            </p>
                        <br>
                            <tournament-bracket
                                :rounds="{{{ $tournament->rounds }}}">
                            </tournament-bracket>
                    </div>
                </div>
            </div>
        </div>
    </div>
</tournamentdetail>
@endsection
