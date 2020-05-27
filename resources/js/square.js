import config from './config'

$(function() {
    const $nonceForm = $('#nonce-form')

    $('#tournament-reg-form').on('submit', function(event) {
        event.preventDefault()
        const form = this

        // Show confirmation form before submitting payment
        $('#confirm-payment-modal').modal()

        $('#submit-payment-btn').click(function(clickEvent) {
            // Submit the tournament payment form
            form.submit()
        })
    })

    if (!window.SqPaymentForm) {
        return
    }

    // Set the application ID
    let applicationId = config.SQUARE_APP_ID

    function buildForm(form) {
        if (SqPaymentForm.isSupportedBrowser()) {
            form.build()
            form.recalculateSize()
        }
    }

    /*
     * function: requestCardNonce
     *
     * requestCardNonce is triggered when the "Pay with credit card" button is
     * clicked
     *
     * Modifying this function is not required, but can be customized if you
     * wish to take additional action when the form button is clicked.
     */
    function requestCardNonce(event) {
        // Don't submit the form until SqPaymentForm returns with a nonce
        event.preventDefault()

        // Request a nonce from the SqPaymentForm object
        paymentForm.requestCardNonce()
    }

    // Create and initialize a payment form object
    let paymentForm = new SqPaymentForm({
        // Initialize the payment form elements
        applicationId: applicationId,
        inputClass: 'sq-input',
        autoBuild: false,

        // Customize the CSS for SqPaymentForm iframe elements
        inputStyles: [
            {
                fontSize: '16px',
                padding: '16px',
                color: '#373F4A',
                backgroundColor: 'transparent',
                lineHeight: '24px',
                placeholderColor: '#CCC',
                _webkitFontSmoothing: 'antialiased',
                _mozOsxFontSmoothing: 'grayscale',
            },
        ],

        // Initialize the credit card placeholders
        cardNumber: {
            elementId: 'sq-card-number',
            placeholder: '• • • •  • • • •  • • • •  • • • •',
        },
        cvv: {
            elementId: 'sq-cvv',
            placeholder: 'CVV',
        },
        expirationDate: {
            elementId: 'sq-expiration-date',
            placeholder: 'MM/YY',
        },
        postalCode: {
            elementId: 'sq-postal-code',
            placeholder: '12345',
        },

        // SqPaymentForm callback functions
        callbacks: {
            /*
             * callback function: cardNonceResponseReceived
             * Triggered when: SqPaymentForm completes a card nonce request
             */
            cardNonceResponseReceived: function(errors, nonce, cardData) {
                const $cardNonce = $('#card-nonce')
                const $amount = $('#amount')

                const amountErrorData = { message: 'Please check the amount' }

                /*
                The amount is invalid if it fails the form validation or
                cannot be parsed as a floating point value.
                 */
                if (!$amount[0].checkValidity() || window.isNaN($amount.val())) {
                    errors = errors ? errors : []
                    errors.push(amountErrorData)
                }

                if (errors) {
                    // Log errors from nonce generation to the Javascript console
                    console.log('Encountered errors:')
                    errors.forEach(function(error) {
                        console.log('  ' + error.message)
                        alert(error.message)
                    })

                    return
                }
                // Assign the nonce value to the hidden form field
                $cardNonce.val(nonce)

                // Show confirmation form before submitting payment
                $('#confirm-payment-modal').modal()
            },

            /*
             * callback function: unsupportedBrowserDetected
             * Triggered when: the page loads and an unsupported browser is detected
             */
            unsupportedBrowserDetected: function() {
                /* PROVIDE FEEDBACK TO SITE VISITORS */
            },

            /*
             * callback function: inputEventReceived
             * Triggered when: visitors interact with SqPaymentForm iframe elements.
             */
            inputEventReceived: function(inputEvent) {
                switch (inputEvent.eventType) {
                    case 'focusClassAdded':
                        /* HANDLE AS DESIRED */
                        break
                    case 'focusClassRemoved':
                        /* HANDLE AS DESIRED */
                        break
                    case 'errorClassAdded':
                        document.getElementById('error').innerHTML =
                            'Please fix card information errors before continuing.'
                        break
                    case 'errorClassRemoved':
                        /* HANDLE AS DESIRED */
                        document.getElementById('error').style.display = 'none'
                        break
                    case 'cardBrandChanged':
                        /* HANDLE AS DESIRED */
                        break
                    case 'postalCodeChanged':
                        /* HANDLE AS DESIRED */
                        break
                }
            },

            /*
             * callback function: paymentFormLoaded
             * Triggered when: SqPaymentForm is fully loaded
             */
            paymentFormLoaded: function() {
                /* HANDLE AS DESIRED */
                //console.log("The form loaded!");
            },
        },
    })

    $('#sq-creditcard').click(function(event) {
        requestCardNonce(event)
    })
    $('#submit-payment-btn').click(function(event) {
        // POST the nonce form to the payment processing page
        $nonceForm[0].submit()
    })
    buildForm(paymentForm)
})
