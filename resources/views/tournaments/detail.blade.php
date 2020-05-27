@extends('spark::layouts.app')

@section('content')

<div class="container" style="max-width: 80em;">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="card">
                <div class="card-header"><h1>{{ $tournament->name}}</h1></div>

                <div class="card-body">

                    @if (session('tk_message_status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('tk_message_status') }}
                        </div>
                    @endif

                    <h3>Details:</h3>

                    <h6>Game: <span class="text-muted">{{ $tournament->gameType->name }}</span> </h6>
                    <h6>Type: <span class="text-muted">{{ $tournament_types[$tournament->tournament_type] }}</span> </h6>
                    <h6>Notes: <span class="text-muted">{{ $tournament->description}}</span> </h6>
                    <h6>Start Date/Time: <span class="text-muted">{{ date('m/d/Y g:i A', strtotime($tournament->start_datetime)) }}</span> </h6>
                    <h6>Winner: <span class="text-muted">{{ $tournament->winner_gamer_tag }}</span> </h6>
                    <h6>Admin: <span class="text-muted">{{ $tournament->createdByPlayer->gamer_tag }}</span> </h6>
                    <p>
                        @if ($tournament->mayEdit())
                            <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}/edit" role="button">Edit Tournament</a>
                            &nbsp;&nbsp;
                        @endif
                        @if ($tournament->mayDelete())
                            &nbsp;&nbsp;
                            <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}/delete" role="button">Delete Tournament</a>
                        @endif
                        </p>
                    <br>

                    <h3>Players:</h3>
                    <h6>Available slots: <span class="text-muted">{{ $tournament->available_slots }} of {{ $tournament->total_slots }}</span> </h6>
                    <h6>Registered players: <span class="text-muted">{{ $tournament->getRegisteredPlayerList() }}</span> </h6>
                    <p>
                        @if ($tournament->mayRegister())
                            <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}/register" role="button">Register</a>
                        @endif
                        </p>
                    <br>

                    @if (! $tournament->winner_gamer_tag)
                        <h3>Upcoming Matchups:</h3>

                        <div class="table-responsive">
                          <table class="table table-striped table-dark">
                              <thead>
                                <tr>
                                  <th scope="col">Round</th>
                                  <th scope="col">Match</th>
                                  <th scope="col">Player 1</th>
                                  <th scope="col">Player 2</th>
                                  <th scope="col">&nbsp;</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($tournament->matches()->whereNull('winner_player_id')->orderBy('round')->orderBy('number')->get() as $match)
                                    <tr>
                                        <td>{{ $match->round }}</td>
                                        <td>{{ $match->number }}</td>
                                        <td>{{ $match->player_1_gamer_tag }}</td>
                                        <td>{{ $match->player_2_gamer_tag }}</td>
                                        <td>
                                            @if ($match->mayPostResult())
                                                <a href="/matches/{{ $match->id }}/edit" >Post Result</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                              </tbody>
                          </table>
                        </div>
                    @endif

                    <h3>Results:</h3>

                    <div class="table-responsive">
                      <table class="table table-striped table-dark display compact">
                          <thead>
                            <tr>
                              <th scope="col">Round</th>
                              <th scope="col">Match</th>
                              <th scope="col">Winner</th>
                              <th scope="col">Score</th>
                              <th scope="col">Loser</th>
                              <th scope="col">Score</th>
                              <th scope="col">Posted</th>
                              <th scope="col">Result Screen</th>
                              <th scope="col">&nbsp;</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($tournament->matches()->whereNotNull('winner_player_id')->get() as $match)
                                <tr>
                                    <td>{{ $match->round }}</td>
                                    <td>{{ $match->number }}</td>
                                    <td>{{ $match->getWinnerGamerTag() }}</td>
                                    <td>{{ $match->getWinnerScore() }}</td>
                                    <td>{{ $match->getLoserGamerTag() }}</td>
                                    <td>{{ $match->getLoserScore() }}</td>
                                    <td>{{ date('m/d/Y g:i A', strtotime($match->result_posted_at)) }}</td>
                                    <td>
                                        @if ($match->existsResultScreen())
                                            <img class="tk-thumb" src="{{ $match->getResultScreenUrl() }}">
                                        @endif
                                    </td>
                                    <td>
                                        @if ($match->resultsAreNotConfirmed())
                                            Unconfirmed Result</a>
                                            <br>
                                        @endif
                                        @if ($match->mayConfirmResult())
                                            <a href="/matches/{{$match->id}}/confirmresults">Confirm Result</a>
                                            <br>
                                        @endif
                                        @if ($match->mayEditResult())
                                            <a href="/matches/{{$match->id}}/edit" >Edit Result</a>
                                            <br>
                                        @endif
                                        @if ($match->mayDeleteResult())
                                            <a href="/matches/{{$match->id}}/delete">Delete Result</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
