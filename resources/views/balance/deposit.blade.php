@extends('spark::layouts.app')

@push('scripts')
    <script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
@endpush

@section('content')
    @if($errors->isNotEmpty())
        <div class="alert alert-danger">
            <ul class="m-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif
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
                <div id="deposit-card" class="card card-default">
                    <div class="card-header">Deposit</div>
                    <div class="card-body">
                        <div id="payment-form-container">
                            <div id="sq-ccbox">
                                <form id="nonce-form" novalidate action="{{ route('balance.deposit.post', null, false) }}" method="post">
                                    @csrf
                                    <fieldset>
                                        <span class="label">Card Number</span>
                                        <div id="sq-card-number"></div>

                                        <div class="third">
                                            <span class="label">Expiration</span>
                                            <div id="sq-expiration-date"></div>
                                        </div>

                                        <div class="third">
                                            <span class="label">CVV</span>
                                            <div id="sq-cvv"></div>
                                        </div>

                                        <div class="third">
                                            <span class="label">Postal</span>
                                            <div id="sq-postal-code"></div>
                                        </div>

                                        <div class="third">
                                            <span class="label">Amount</span>
                                            <div class="m-2">
                                                <span class="pr-1">$</span>
                                                <input
                                                        id="amount"
                                                        name="amount"
                                                        type="text"
                                                        placeholder="1.00"
                                                        required
                                                        pattern="[0-9]+(\.[0-9][0-9])?"
                                                        value="{{ session('amount') }}"
                                                />
                                            </div>
                                        </div>
                                    </fieldset>

                                    <button id="sq-creditcard" class="button-credit-card">Pay</button>

                                    <div id="error"></div>

                                    <!--
                                      After a nonce is generated it will be assigned to this hidden input field.
                                    -->
                                    <input type="hidden" id="card-nonce" name="nonce">
                                </form>
                            </div> <!-- end #sq-ccbox -->

                        </div> <!-- end #form-container -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
