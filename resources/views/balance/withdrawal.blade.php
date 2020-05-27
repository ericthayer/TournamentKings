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
        <!-- Deposit Page -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div id="withdrawal-card" class="card card-default">
                    <div class="card-header">Withdrawal</div>
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
                        <form id="user-withdrawal-form" method="post" action="{{ route('balance.withdrawal.post', null, false) }}">
                            @csrf
                            <div class="form-group row">
                                <label for="amount" class="col-2">Amount</label>
                                    <input
                                        type="text"
                                        id="amount"
                                        name="amount"
                                        class="form-control col-3"
                                        placeholder="1.00"
                                        pattern="[0-9]+(\.[0-9][0-9])?"
                                        required
                                        value="{{ old('amount') }}"
                                    />
                            </div>
                            <div class="form-group row">
                                <label for="withdrawal_type" class="col-2">Payment Method</label>
                                <select
                                    id="withdrawal_type_name"
                                    name="withdrawal_type_name"
                                    class="form-control col-2"
                                >
                                    <option value="">{{ __('tournaments.please-select') }}</option>
                                    @foreach ($withdrawal_types as $name => $displayName)
                                        <option
                                                value="{{ $name }}"
                                                {{ (
                                                        old('withdrawal_type_name') == $name ?
                                                        "selected" : ""
                                                    ) }}>
                                            {{ $displayName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="phone_number" class="col-2">Phone #</label>
                                    <input
                                            type="text"
                                            id="phone_number"
                                            name="phone_number"
                                            class="form-control col-3"
                                            placeholder="3035551234"
                                            pattern="((\(\d{3}\)?)|(\d{3}))([ -.x]?)(\d{3})([ -.x]?)(\d{4})"
                                            value="{{ old('phone_number') }}"
                                    />
                                    <small class="col-3">Required for Square Cash</small>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-2">Email</label>
                                <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control col-3"
                                        placeholder="me@example.com"
                                        value="{{ old('email') }}"
                                />
                                <small class="col-3">Optional alternate email. Must be verified. Leave blank to use your account email.</small>
                            </div>
                            <div class="form-group row">
                                <div class="btn-group col-2">
                                    <button class="btn btn-primary">
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
@endsection
