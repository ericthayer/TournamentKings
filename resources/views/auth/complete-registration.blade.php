@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">

                    <form method="POST" action="{{ route('complete-register.update', $user->id) }}">
                        @csrf
                        <input id="redirect" type="hidden" name="redirect" value="{{ old('redirect', $_GET['redirect']) }}" >
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name) }}" required>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" required>
                                <span class="text-warning" role="warning">
                                    This email should be the same as your PayPal email for payouts.
                                </span>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                <span class="text-warning" role="warning">
                                    The Password must contain at least: <br />
                                    8 characters, one lowercase letter, one uppercase letter, at least one digit.
                                </span>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gamer_tag" class="col-md-4 col-form-label text-md-right">{{ __('Gamer Tag') }}</label>

                            <div class="col-md-6">
                                <input id="gamer_tag" type="text" class="form-control{{ $errors->has('gamer_tag') ? ' is-invalid' : '' }}" name="gamer_tag" value="{{ old('gamer_tag', $user->player->gamer_tag) }}" required>

                                @if ($errors->has('gamer_tag'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gamer_tag') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

{{--                        <div class="form-group row">--}}
{{--                            <label for="platform_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Platform Type') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <select id="platform_type_id" name="platform_type_id" required class="form-control{{ $errors->has('platform_type_id') ? ' is-invalid' : '' }}">--}}
{{--                                    <option value="">Please Select</option>--}}
{{--                                        @foreach ($platform_types as $item)--}}
{{--                                            <option value="{{ $item->id }}" {{ (old('platform_type_id', $user->player->platform_type_id) == $item->id ? "selected" : "") }}>{{ $item->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                </select>--}}

{{--                                @if ($errors->has('platform_type_id'))--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $errors->first('platform_type_id') }}</strong>--}}
{{--                                    </span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="form-group row">
                            <label for="platform_network" class="col-md-4 col-form-label text-md-right">{{ __('Platform Network') }}</label>

                            <div class="col-md-6">
                                <select id="platform_network" name="platform_network" required class="form-control{{ $errors->has('platform_network') ? ' is-invalid' : '' }}">
                                    <option value="">Please Select</option>
                                    @foreach ($platform_networks as $item)
                                        <option value="{{ $item }}" {{ (old('platform_network', $user->player->platform_network) == $item ? "selected" : "") }}>{{ $item }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('platform_network'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('platform_network') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="platform_network_id" class="col-md-4 col-form-label text-md-right">{{ __('Platform Network ID') }}</label>

                            <div class="col-md-6">
                                <input id="platform_network_id" type="text" class="form-control{{ $errors->has('platform_network_id') ? ' is-invalid' : '' }}" name="platform_network_id" value="{{ old('platform_network_id', $user->player->platform_network_id) }}" required>

                                @if ($errors->has('platform_network_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('platform_network_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location" class="col-md-4 col-form-label text-md-right">{{ __('Location') }}</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="typeahead form-control{{ $errors->has('location_id') ? ' is-invalid' : '' }}" name="location" value="{{ old('location') }}" placeholder="City, State" required autocomplete="location">
                                <input id="location_id" type="hidden" name="location_id" value="{{ old('location_id') }}" >
                                <span class="text-warning" role="warning">
                                    Gamers in Arizona, Iowa, and Louisiana are not allowed to join currently.
                                </span>
                                @if ($errors->has('location_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('location_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Terms And Conditions -->
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
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
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
