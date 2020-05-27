@extends('spark::layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>Confirm Match Results</h1></div>

                <div class="card-body">
                    <form method="POST" action="/matches/{{ $match->id }}/confirmresults">
                        @csrf

                        <p>Are these results correct?</p>
                        <h6>Round: <span class="text-muted">{{ $match->round }}</span> </h6>
                        <h6>Match: <span class="text-muted">{{ $match->number }}</span> </h6>
                        <h6>Winner: <span class="text-muted">{{ $match->getWinnerGamerTag() }}</span> </h6>
                        <h6>Score: <span class="text-muted">{{ $match->getWinnerScore() }}</span> </h6>
                        <h6>Loser: <span class="text-muted">{{ $match->getLoserGamerTag() }}</span> </h6>
                        <h6>Score: <span class="text-muted">{{ $match->getLoserScore() }}</span> </h6>
                        <h6>Posted: <span class="text-muted">{{ date('m/d/Y g:i A', strtotime($match->result_posted_at)) }}</span> </h6>
                        <h6>Result Screen:</h6>
                            @if ($match->existsResultScreen())
                                <img class="tk-thumb" src="{{ $match->result_screen_url }}">
                            @endif
                        <br><br>

                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <a class="btn btn-primary" href="/tournaments/{{ $match->tournament_id }}" role="button">Cancel</a>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
