import $ from 'jquery';
import successTemplate from './templates/successTemplate';
import errorTemplate from './templates/errorTemplate';

class StripeService {
    public card: string;
    public stripe: any;
    public elements: any;

    constructor(publishableApiKey: string) {
        this.card = '';
        try {
            /**
             * Stripe client side doesn't seem to have types yet
             * The 'Stripe(publishableApiKey);' comes from
             * <script src="https://js.stripe.com/v3/"></script> in the head of app.blade of this package
             */
            // @ts-ignore
            this.stripe = Stripe(publishableApiKey);
            this.elements = this.stripe.elements();
        } catch (error) {
            // console.log(error);
        }
    }

    public formLoaded() {
        // Set up Stripe.js and Elements to use in checkout form
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a',
            },
        };

        try {
            var card = this.elements.create('card', { style: style });
            this.card = card;

            card.mount('#card-element');

            card.addEventListener('change', ({ error }: { error: { message: string } }) => {
                const displayError = document.getElementById('card-errors') || { textContent: 'An error occured.' };
                if (error) {
                    displayError.textContent = error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        } catch (error) {
            // console.log(error);
        }
    }

    public formSubmitListener(clientSecret: string) {
        var form = document.getElementById('payment-form');
        var self = this;

        let loading = document.getElementById('loadingDiv');
        if (loading) loading.style.display = 'none';

        if (!self.stripe || !form) {
            // console.log('Stripe is not defined.');
            return;
        }

        form.addEventListener('submit', function (ev) {
            ev.preventDefault();

            // Loading
            if (loading) loading.style.display = 'flex';

            self.stripe
                .confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: self.card,
                        billing_details: {
                            name: 'Bob Rosen',
                        },
                    },
                })
                .then(function (result: { error: { message: string }; paymentIntent: { status: string } }) {
                    if (loading) loading.style.display = 'none';

                    if (result.error) {
                        // Show error to your customer (e.g., insufficient funds)
                        $('#stripeFormTemplate').append(errorTemplate(result.error.message));

                        // console.log(result.error.message);
                    } else {
                        // The payment has been processed!
                        if (result.paymentIntent.status === 'succeeded') {
                            // Show a success message to your customer
                            // There's a risk of the customer closing the window before callback
                            // execution. Set up a webhook or plugin to listen for the
                            // payment_intent.succeeded event that handles any business critical
                            // post-payment actions.
                            $('#stripeFormTemplate').html(successTemplate('Payment successful.'));
                        }
                    }
                })
                .catch((error: {}) => {
                    if (loading) loading.style.display = 'none';

                    $('.checkout-container').append(errorTemplate('Unexpected error occured. Please try again.'));

                    // console.log(error);
                });
        });
    }
}
export { StripeService };
