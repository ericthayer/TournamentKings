@extends('layouts.app')

@section('content')

<section class="form">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="title">{{ __('auth.login') }}</h2>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group row pb-3">
                        <label for="email" class="col-form-label">{{ __('auth.email') }}</label>

                        <div class="col-form-input">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-form-label">{{ __('auth.password') }}</label>

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
                        <div class="settings col-xs-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('auth.remember-me') }}
                                </label>
                            </div>
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('auth.forgot') }}
                            </a>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="actions col-xs-12">
                            <button type="submit" class="btn-primary-dark">
                                {{ __('auth.sign-in') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
