@extends('spark::layouts.app')

@section('content')
<?php
    if ($tournament->id) {
        $tournament_title = __('tournaments.edit');
        $tournament_link = '/tournaments/' . $tournament->id;
        $tournament_button_title = 'Update';
    } else {
        $tournament_title = __('tournaments.create');
        $tournament_link = route('tournaments.store', null, null);
        $tournament_button_title = 'Create';
        $tournament->start_datetime = NULL;
    }
?>
<createtournament :user="user" :tournament="{{ $tournament }}" :errors="{{ $errors->toJson() }}" inline-template>
    <div class="container">
        <!-- Create/Edit Tournament Page -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">{{ $tournament_title }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ $tournament_link }}">
                            @csrf
                            @if ($tournament->id)
                                @method('PUT')
                            @endif

                            <div class="form-group row">
                                <label for="tournament_name" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.tournaments') }}</label>

                                <div class="col-md-6">
                                    <input id="tournament_name" type="text" :class="{'is-invalid': form.errors.has('tournament_name'), 'form-control': true}" name="tournament_name" v-model="form.name" autocomplete="tournament-form-name">

                                    <span class="help-block" v-show="form.errors.has('name')">
                                        @{{ form.errors.get('name') }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="game_type_id" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.game') }}</label>

                                <div class="col-md-6">
                                    <select id="game_type_id" name="game_type_id" :class="{'is-invalid': form.errors.has('game_type_id'), 'form-control': true}" v-model="form.game_type_id" >
                                        <option value="">{{ __('tournaments.please-select') }}</option>
                                            @foreach ($game_types as $game_type)
                                                <option value="{{ $game_type->id }}" {{ (old('game_type_id', $tournament->game_type_id) == $game_type->id ? "selected" : "") }}>{{ $game_type->name}}</option>
                                            @endforeach
                                    </select>

                                    <span class="help-block" v-show="form.errors.has('game_type_id')">
                                        @{{ form.errors.get('game_type_id') }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tournament_type" class="col-md-4 col-form-label text-md-right">Type</label>
                                <div class="col-md-6">
                                    <select id="tournament_type" name="tournament_type"  :class="{'is-invalid': form.errors.has('tournament_type'), 'form-control': true}" v-model="form.tournament_type">
                                        <option value="">{{ __('tournaments.please-select') }}</option>
                                            @foreach ($tournament_types as $key => $tournament_type)
                                                <option value="{{ $key }}" {{ (old('tournament_type', $tournament->tournament_type) == $key ? "selected" : "") }}>{{ $tournament_type }}</option>
                                            @endforeach
                                    </select>

                                    <span class="help-block" v-show="form.errors.has('tournament_type')">
                                        @{{ form.errors.get('tournament_type') }}
                                    </span>
                                </div>
                            </div>

                            <div class="tk-private-password" v-show="showPasswords">
                                <div class="form-group row">
                                    <label for="tournament_password" class="col-md-4 col-form-label text-md-right">{{ __('auth.password') }}</label>

                                    <div class="col-md-6">
                                        <input id="tournament_password" type="password" :class="{'is-invalid': form.errors.has('tournament_password'), 'form-control': true}" v-model="form.password" name="tournament_password" autocomplete="tournament-form-password">

                                        <span class="help-block" v-show="form.errors.has('password')">
                                            @{{ form.errors.get('password') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="tournament_password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('auth.confirm-password') }}</label>

                                    <div class="col-md-6">
                                        <input id="tournament_password_confirmation" type="password" :class="{'is-invalid': form.errors.has('tournament_password_confirmation'), 'form-control': true}" v-model="form.password_confirmation" name="tournament_password_confirmation" autocomplete="tournament-form-password-confirmation">
                                        <span class="help-block" v-show="form.errors.has('password_confirmation')">
                                            @{{ form.errors.get('password_confirmation') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Notes</label>

                                <div class="col-md-6">
                                <textarea id="description" name="description" :class="{'is-invalid': form.errors.has('description'), 'form-control': true}" v-model="form.description"></textarea>

                                <span class="help-block" v-show="form.errors.has('description')">
                                    @{{ form.errors.get('description') }}
                                </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="datetimepicker1" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.start-date-time') }}</label>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div>
                                            <input id="start_date" type="date" class="form-control" name="start_date" v-model="date" >
                                        </div>
                                        <div>
                                            <input id="start_time" type="time" class="form-control" name="start_time" v-model="time" >
                                        </div>

                                        <input type="hidden" id="start_datetime" name="start_datetime"  v-model="datetime" >
                                        <span class="help-block" v-show="form.errors.has('start_datetime')">
                                            @{{ form.errors.get('start_datetime') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="total_slots" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.available-slots') }}</label>

                                <div class="col-md-6">
                                    <input id="total_slots" type="number" :class="{'is-invalid': form.errors.has('total_slots'), 'form-control': true}" v-model="form.total_slots" name="total_slots" >
                                    <span class=help-block" v-show="slotsNotPowerOfTwo">
                                        Any multiple other than a power of 2 will result in a bye position, and the first users to register for the tournament will be assigned the bye.
                                    </span>
                                    <span class="help-block" v-show="form.errors.has('total_slots')">
                                        @{{ form.errors.get('total_slots') }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="entry_fee_type" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.entry-fee-type') }}</label>
                                <div class="col-md-6">
                                    <select id="entry_fee_type_name" name="entry_fee_type_name"  :class="{'is-invalid': form.errors.has('entry_fee_type_name'), 'form-control': true}" v-model="form.entry_fee_type_name">
                                        <option value="">{{ __('tournaments.please-select') }}</option>
                                        @foreach ($entry_fee_types as $name => $displayName)
                                            <option
                                                value="{{ $name }}"
                                                {{ (
                                                        old('entry_fee_type', $tournament->entryFeeType->name ?? '') == $name ?
                                                        "selected" : ""
                                                    ) }}>
                                                    {{ $displayName }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span class="help-block" v-show="form.errors.has('entry_fee_type_name')">
                                        @{{ form.errors.get('entry_fee_type_name') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flat-fee-container" v-show="showEntryFee">
                                <div class="form-group row">
                                    <label for="entry_fee" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.entry-fee') }}</label>
                                    <div class="col-md-6">
                                        <input
                                            id="entry_fee"
                                            type="text"
                                            :class="{'is-invalid': form.errors.has('entry_fee'), 'form-control': true}"
                                            v-model="form.entry_fee"
                                            name="entry_fee"
                                            autocomplete="tournament-form-entry-fee"
                                        >
                                        <span class="help-block" v-show="form.errors.has('entry_fee')">
                                            @{{ form.errors.get('entry_fee') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="target-pot-container" v-show="showTargetPot">
                                <div class="form-group row">
                                    <label for="target_pot" class="col-md-4 col-form-label text-md-right">{{ __('tournaments.target-pot') }}</label>
                                    <div class="col-md-6">
                                        <input
                                            id="target_pot"
                                            type="text"
                                            :class="{'is-invalid': form.errors.has('target_pot'), 'form-control': true}"
                                            v-model="form.target_pot"
                                            name="target_pot"
                                            autocomplete="tournament-form-target-pot"
                                        >
                                        <span class="help-block" v-show="form.errors.has('target_pot')">
                                            @{{ form.errors.get('target_pot') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <a class="btn btn-primary" href="{{ $tournament_link }}" role="button">{{ __('auth.cancel') }}</a>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-primary" @click="register" :disabled="form.busy">
                                        {{ __('auth.submit') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</createtournament>
@endsection
