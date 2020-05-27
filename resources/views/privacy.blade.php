@extends('spark::layouts.app')

@section('content')
<div class="container">
    <!-- Terms of Service -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">{{__('Privacy Policy')}}</div>

                <div class="card-body terms-of-service">
                    {!! $privacy !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
