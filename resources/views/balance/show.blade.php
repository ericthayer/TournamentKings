@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <!-- Create/Edit Tournament Page -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">My Balance</div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {!! session('success') !!}
                            </div>
                        @endif
                        <p class="card-text">
                            <a
                                class="btn btn-primary"
                                href="{{ route('balance.deposit.create', null, false) }}"
                                role="button">{{ __('balance.deposit') }}</a>
                            <a
                                class="btn btn-primary ml-3"
                                href="{{ route('balance.withdrawal.create', null, false) }}"
                                role="button">{{ __('balance.withdrawal') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
