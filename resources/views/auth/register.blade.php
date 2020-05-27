@extends('layouts.app')

@section('content')

<section class="form">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="title">{{ __('Register') }}</h2>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-form-label">{{ __('Name') }}</label>

                        <div class="col-form-input">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>

                        <div class="col-form-input">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-form-label">{{ __('Password') }}</label>

                        <div class="col-form-input">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-form-label">{{ __('Confirm Password') }}</label>

                        <div class="col-form-input">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

{{--                        <div class="form-group row">--}}
{{--                            <label for="access_code" class="col-form-label">{{ __('Access Code') }}</label>--}}

{{--                            <div class="col-form-input">--}}
{{--                                <input id="access_code" type="text" class="form-control{{ $errors->has('access_code') ? ' is-invalid' : '' }}" name="access_code" value="{{ old('access_code') }}">--}}

{{--                                @if ($errors->has('access_code'))--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $errors->first('access_code') }}</strong>--}}
{{--                                    </span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    <div class="form-group row flex-row">
                        <div class="form-col">
                            <label for="gamer_tag" class="col-form-label">{{ __('Gamer Tag') }}</label>

                            <div class="col-form-input">
                                <input id="gamer_tag" type="text" class="form-control{{ $errors->has('gamer_tag') ? ' is-invalid' : '' }}" name="gamer_tag" value="{{ old('gamer_tag') }}" required>

                                @if ($errors->has('gamer_tag'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gamer_tag') }}</strong>
                                    </span>
                                @endif
                            </div>

                    </div>

                    <div class="form-col select-input">
                        <label for="platform_type_id" class="col-form-label">{{ __('Platform Type') }}</label>

                        <div class="col-form-input select">
                            <select id="platform_type_id" name="platform_type_id" required class="form-control{{ $errors->has('platform_type_id') ? ' is-invalid' : '' }}">
                                <option value="">Please Select</option>
                                    @foreach ($platform_types as $item)
                                        <option value="{{ $item->id }}" {{ (old('platform_type_id') == $item->id ? "selected" : "") }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>

                            @if ($errors->has('platform_type_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('platform_type_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                    <div class="form-group row">
                        <label for="location" class="col-form-label">{{ __('Location') }}</label>

                        <div class="col-form-input">
                            <input id="location" type="text" class="typeahead form-control{{ $errors->has('location_id') ? ' is-invalid' : '' }}" name="location" value="{{ old('location') }}" placeholder="City, State" required autocomplete="location">
                            <input id="location_id" type="hidden" name="location_id" value="{{ old('location_id') }}" >

                            <div class="text-warning pt-3" role="warning">
                                Gamers in Arizona, Iowa, and Louisiana are not allowed to join currently.
                            </div>

                            @if ($errors->has('location_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('location_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Terms And Conditions -->
                    <div class="form-group row">
                        <div class="settings col-xs-12">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" id="terms" name="terms" class="form-check-input">
                                    {!! __('I Accept :linkOpen The Terms Of Service :linkClose', ['linkOpen' => '<a href="/terms" target="_blank">', 'linkClose' => '</a>']) !!}
                                </label>
                                @if ($errors->has('terms'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('terms') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="actions col-xs-12">
                            <button type="submit" class="btn btn-primary-dark">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
