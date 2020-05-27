@extends('layouts.app')

@section('content')
<?php
    if ($tournament->id) {
        $tournament_title = 'Edit Tournament';
        $tournament_link = '/tournaments/' . $tournament->id;
        $tournament_button_title = 'Update';
        $tournament->start_datetime = date('m/d/Y g:i A', strtotime($tournament->start_datetime));
    } else {
        $tournament_title = 'Create Tournament';
        $tournament_link = '/tournaments';
        $tournament_button_title = 'Create';
        $tournament->start_datetime = NULL;
    }
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h1>{{ $tournament_title }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ $tournament_link }}">
                        @csrf
                        @if ($tournament->id)
                            @method('PUT')
                        @endif

                        <div class="form-group row">
                            <label for="tournament_name" class="col-md-4 col-form-label text-md-right">Tournament</label>

                            <div class="col-md-6">
                                <input id="tournament_name" type="text" class="form-control{{ $errors->has('tournament_name') ? ' is-invalid' : '' }}" name="tournament_name" value="{{ old('tournament_name', $tournament->name) }}" required>

                                @if ($errors->has('tournament_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tournament_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="game_type_id" class="col-md-4 col-form-label text-md-right">Game</label>

                            <div class="col-md-6">
                                <select id="game_type_id" name="game_type_id"  class="form-control{{ $errors->has('game_type_id') ? ' is-invalid' : '' }}" required>
                                    <option value="">Please Select</option>
                                        @foreach ($game_types as $game_type)
                                            <option value="{{ $game_type->id }}" {{ (old('game_type_id', $tournament->game_type_id) == $game_type->id ? "selected" : "") }}>{{ $game_type->name }}</option>
                                        @endforeach
                                </select>

                                @if ($errors->has('game_type_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('game_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tournament_type" class="col-md-4 col-form-label text-md-right">Type</label>

                            <div class="col-md-6">
                                <select id="tournament_type" name="tournament_type"  class="form-control{{ $errors->has('tournament_type') ? ' is-invalid' : '' }}" required>
                                    <option value="">Please Select</option>
                                        @foreach ($tournament_types as $key => $tournament_type)
                                            <option value="{{ $key }}" {{ (old('tournament_type', $tournament->tournament_type) == $key ? "selected" : "") }}>{{ $tournament_type }}</option>
                                        @endforeach
                                </select>

                                @if ($errors->has('tournament_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tournament_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="tk-private-password" @if (old('tournament_type', $tournament->tournament_type) != 'private') style="display:none;" @endif>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tournament_description" class="col-md-4 col-form-label text-md-right">Notes</label>

                            <div class="col-md-6">
                                <textarea id="tournament_description" name="tournament_description" class="form-control{{ $errors->has('tournament_description') ? ' is-invalid' : '' }}" required>{{ old('tournament_description', $tournament->description) }}</textarea>

                                @if ($errors->has('tournament_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('tournament_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datetimepicker1" class="col-md-4 col-form-label text-md-right">Start Date/Time (MST)</label>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input {{ $errors->has('start_datetime') ? ' is-invalid' : '' }}" data-target="#datetimepicker1" name="start_datetime" value="{{ old('start_datetime', $tournament->start_datetime) }}" required />
                                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>

                                        @if ($errors->has('start_datetime'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('start_datetime') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="total_slots" class="col-md-4 col-form-label text-md-right">Available Slots</label>

                            <div class="col-md-6">
                                <input id="total_slots" type="number" class="form-control{{ $errors->has('total_slots') ? ' is-invalid' : '' }}" name="total_slots" value="{{ old('total_slots', $tournament->total_slots) }}" required>

                                @if ($errors->has('total_slots'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('total_slots') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a class="btn btn-primary" href="{{ $tournament_link }}" role="button">Cancel</a>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary">
                                    {{ $tournament_button_title }}
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
