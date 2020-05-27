@extends('spark::layouts.app')

@section('content')
<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirm-payment-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-payment-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to submit payment?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Would you like to pay now?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-payment-btn">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>Tournament Registration</h1></div>

                <div class="card-body">
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
                    <form method="POST" id="tournament-reg-form" action="{{route('register.post', $tournament, false)}}">
                        @csrf

                        <h6>Name: <span class="text-muted">{{ $tournament->name }}</span> </h6>
                        <h6>Game: <span class="text-muted">{{ $tournament->gameType->name }}</span> </h6>
                        <h6>Type: <span class="text-muted">{{ $tournament_types[$tournament->tournament_type] }}</span> </h6>
                        <h6>Notes: <span class="text-muted">{{ $tournament->description}}</span> </h6>
                        <h6>Start Date/Time: <span class="text-muted">{{ date('m/d/Y g:i A', strtotime($tournament->start_datetime)) }}</span> </h6>
                        <h6>Available Slots: <span class="text-muted">{{ $tournament->total_slots }}</span> </h6>
                        <h6>Admin: <span class="text-muted">{{ $tournament->createdByPlayer->gamer_tag }}</span> </h6>

                        @if ($tournament->tournament_type == 'private')
                            <div class="form-inline">
                                <label for="password" class="form-label">Password:</label>&nbsp;&nbsp;
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif

                        <?php
                            if ($tournament->entryFeeType->is_flat_fee) {
                                $value = $tournament->display_entry_fee;
                            } else if ($tournament->entryFeeType->is_target_pot) {
                                $value = $tournament->display_player_deposit;
                            }
                        ?>

                        @if (!$tournament->entryFeeType->is_free)
                            <div class="form-group">
                                <small>
                                    @if($tournament->entryFeeType->is_flat_fee)
                                        {{ __('tournaments.entry-fee-display', ['fee' => $tournament->display_entry_fee]) }}
                                    @endif
                                    @if($tournament->entryFeeType->is_target_pot)
                                        <span class="d-inline-block mr-3">
                                                {{ __('tournaments.target-pot-display', ['pot' => $tournament->display_target_pot]) }}
                                             </span>
                                        <span class="d-inline-block">
                                                 {{ __('tournaments.player-deposit-display', ['deposit' => $tournament->display_player_deposit]) }}
                                             </span>
                                    @endif
                                </small>
                            </div>
                        @endif

                        <br>

                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <a class="btn btn-primary" href="/tournaments/{{$tournament->id}}" role="button">Cancel</a>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">
                                    Register
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
