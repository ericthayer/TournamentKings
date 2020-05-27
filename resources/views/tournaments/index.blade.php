@extends('spark::layouts.app')

@section('content')
    <div class="container" style="max-width: 80em;">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header"><h1>Tournaments</h1></div>

                <div class="card-body">

                    @if (session('tk_message_status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('tk_message_status') }}
                        </div>
                    @endif

                    <p><a class="btn btn-primary" href="/tournaments/create" role="button">Create Tournament</a></p>
                    <br>

                    <div class="table-responsive">
                      <table id="tournament-datatable" class="table table-striped table-dark display compact">
                          <thead>
                            <tr>
                              <th scope="col">Game</th>
                              <th scope="col">Tournament</th>
                              <th scope="col">Available slots</th>
                              <th scope="col">Start Date/Time</th>
                              <th scope="col">Winner</th>
                              <th scope="col">Admin</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($tournaments as $tournament)
                                <tr>
                                    <td>{{ $tournament->gameType->name }}</td>
                                    <td><a href="/tournaments/{{ $tournament->id }}">{{ $tournament->name }}</a></td>
                                    <td>{{ $tournament->available_slots }} of {{ $tournament->total_slots }}</td>
                                    <td data-order="{{ $tournament->start_datetime }}">{{ date('m/d/Y g:i A', strtotime($tournament->start_datetime)) }}</td>
                                    <td>{{ $tournament->winner_gamer_tag }}</td>
                                    <td>{{ $tournament->createdByPlayer->gamer_tag }}</td>
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
