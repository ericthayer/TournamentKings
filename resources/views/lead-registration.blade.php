@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Early Access') }}</div>

                    <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div style="margin: 0 0 0 12%">
                                <h2>WANT TO JOIN OUR BETA GROUP?</h2>
                                <p >Build this dream with us. All beta group members will get lifetime <br/>
                                    benefits and perks that are actually worthwhile. Any and all help you <br/>can provide will be forever remembered and appreciated.  </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-sec">
                                @foreach ($errors->all() as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{$error}}</strong>
                                    </span><br/>
                                @endforeach
                                <form action="/" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="fname" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input type="text" id="fname" name="Name" class="form-control {{ $errors->has('Name') ? ' is-invalid' : '' }}" placeholder="Name" value="{{ old('Name') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="femail" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                        <div class="col-md-6">
                                            <input type="email" id="femail" name="Email" class="form-control {{ $errors->has('Email') ? ' is-invalid' : '' }}" placeholder="Email" value="{{ old('Email') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fphone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

                                        <div class="col-md-6">
                                            <input type="tel" id="fphone" name="Phone" class="form-control {{ $errors->has('Phone') ? ' is-invalid' : '' }}" placeholder="Phone" value="{{ old('Phone') }}" required>
                                            <input type="hidden" id="fcode" name="Code" class="form-control  {{ $errors->has('Code') ? ' is-invalid' : '' }}" placeholder="Referral Code" value="{{ old('Code', $code) }}">
                                        </div>
                                    </div>



                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-tk">
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
        </div>
    </div>
@endsection