@extends('spark::layouts.app')

@section('content')
<?php
    if ($match->result_posted_at) {
        $match_title = 'Edit Results';
    } else {
        $match_title = 'Post Results';
    }
?>

<div class="container" style="max-width: 80em;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>{{ $match_title }} for Tournament "{{ $match->tournament->name }}", Round {{ $match->round }}, Match {{ $match->number }}</h1>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="/matches/{{ $match->id }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="player_1_gamer_tag" class="col-md-4 col-form-label text-md-right">Gamer Tag</label>

                            <div class="col-md-6">
                                <select id="player_1_gamer_tag" name="player_1_gamer_tag" required class="form-control {{ $errors->has('player_1_gamer_tag') ? ' is-invalid' : '' }}">
                                    <option value="">Please Select</option>
                                    <option value="{{ $match->player_one->id }}" {{ (old('player_1_gamer_tag', $match->player_one->gamer_tag) == $match->player_one->gamer_tag) ? "selected" : "" }}>{{ $match->player_one->gamer_tag }}</option>
                                    <option value="{{ $match->player_two->id }}" {{ (old('player_1_gamer_tag', $match->player_one->gamer_tag) == $match->player_two->gamer_tag) ? "selected" : "" }}>{{ $match->player_two->gamer_tag }}</option>
                                </select>

                                @if ($errors->has('player_1_gamer_tag'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('player_1_gamer_tag') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="player_1_score" class="col-md-4 col-form-label text-md-right">Score</label>

                            <div class="col-md-6">
                                <input id="player_1_score" type="text" class="form-control {{ $errors->has('player_1_score') ? ' is-invalid' : '' }}" name="player_1_score" value="{{ old('player_1_score', $match->player_one->pivot->score) }}" required>

                                @if ($errors->has('player_1_score'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('player_1_score') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="player_2_gamer_tag" class="col-md-4 col-form-label text-md-right">Gamer Tag</label>

                            <div class="col-md-6">
                                <select id="player_2_gamer_tag" name="player_2_gamer_tag" required class="form-control {{ $errors->has('player_2_gamer_tag') ? ' is-invalid' : '' }}">
                                    <option value="">Please Select</option>
                                    <option value="{{ $match->player_one->id }}" {{ (old('player_2_gamer_tag', $match->player_two->gamer_tag) == $match->player_one->gamer_tag) ? "selected" : "" }}>{{ $match->player_one->gamer_tag }}</option>
                                    <option value="{{ $match->player_two->id }}" {{ (old('player_2_gamer_tag', $match->player_two->gamer_tag) == $match->player_two->gamer_tag) ? "selected" : "" }}>{{ $match->player_two->gamer_tag }}</option>
                                </select>

                                @if ($errors->has('player_2_gamer_tag'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('player_2_gamer_tag') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="player_2_score" class="col-md-4 col-form-label text-md-right">Score</label>

                            <div class="col-md-6">
                                <input id="player_2_score" type="text" class="form-control {{ $errors->has('player_2_score') ? ' is-invalid' : '' }}" name="player_2_score" value="{{ old('player_2_score', $match->player_two->pivot->score) }}" required>

                                @if ($errors->has('player_2_score'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('player_2_score') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="result_screen" class="col-md-4 col-form-label text-md-right">Upload Result Screen</label>

                            <div class="col-md-6">
                                <input id="result_screen" type="file" class="form-control{{ $errors->has('result_screen') ? ' is-invalid' : '' }}" name="result_screen" value="{{ old('result_screen') }}"  accept="image/png,image/gif,image/jpg,image/jpeg"
                                    {{ (($match->result_posted_at) ? '' : 'required') }}
                                >

                                @if ($errors->has('result_screen'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('result_screen') }}</strong>
                                    </span>
                                @endif

                                @if ($match->existsResultScreen())
                                    <br>
                                    <img class="tk-thumb" src="{{ $match->result_screen_url }}">
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a class="btn btn-primary" href="/tournaments/{{ $match->tournament_id }}" role="button">Cancel</a>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">
                                    {{ $match_title }}
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
