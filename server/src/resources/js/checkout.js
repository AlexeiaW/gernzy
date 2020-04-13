import successTemplate from './templates/successTemplate';
import stripeFormTemplate from './templates/stripeFormTemplate';
import $ from 'jquery';

class Checkout {
    constructor(graphqlService, cart = null, stripe) {
        this.graphqlService = graphqlService;
        this.cart = cart;
        this.stripe = stripe;
    }

    checkout() {
        // This is to keep the object context of and access it's methods
        var self = this;
        $('#checkout-form').submit(function(event) {
            event.preventDefault();

            // get all the inputs into an array.
            var inputs = $('#checkout-form :input');

            // not sure if you wanted this, but I thought I'd add it.
            // get an associative array of just the values.
            var values = {};
            inputs.each(function() {
                values[this.name] = $(this).val();
            });

            // Checkbox values
            values['use_shipping_for_billing'] = $('#use_shipping_for_billing').prop('checked');
            values['agree_to_terms'] = $('#agree_to_terms').prop('checked');

            self.sendOfCheckoutInfo(values);
        });
    }

    sendOfCheckoutInfo(values) {
        var userToken = localStorage.getItem('userToken');
        let query = ` mutation {
            checkout(input: {
                name: "${values['name']}",
                email: "${values['email']}",
                telephone: "${values['telephone']}",
                mobile: "${values['mobile']}",
                billing_address: {
                    line_1: "${values['billing_address_line_1']}",
                    line_2: "${values['billing_address_line_2']}",
                    state: "${values['billing_address_state']}",
                    postcode: "${values['billing_address_postcode']}",
                    country: "${values['billing_address_country']}"
                },
                shipping_address: {
                    line_1: "${values['shipping_address_line_1']}",
                    line_2: "${values['shipping_address_line_2']}",
                    state: "${values['shipping_address_state']}",
                    postcode: "${values['shipping_address_postcode']}",
                    country: "${values['shipping_address_country']}"
                },
                use_shipping_for_billing: ${values['use_shipping_for_billing']},
                payment_method: "${values['payment_method']}",
                agree_to_terms: ${values['agree_to_terms']},
                notes: "${values['notes']}"
            }){
                event_data
                order {
                    id
                }
            }
        }`;

        return this.graphqlService
            .sendQuery(query, userToken)
            .then(re => {
                // This is the event data passed on from backend
                let eventData;
                let stripeSecretkey;

                try {
                    // Get the event data and parse it
                    eventData = JSON.parse(re.data.checkout.event_data);

                    // Get stripe specific data from event data
                    stripeSecretkey = eventData[0].data.stripe_data;
                } catch (error) {
                    // console.log(error);
                }

                $('.checkout-container').html(successTemplate('Your details have been submitted.'));

                // Now display payment
                $('.checkout-container').html(stripeFormTemplate);
                this.stripe.formLoaded();
                this.stripe.formSubmitListener(stripeSecretkey);

                return re;
            })
            .catch(error => {
                // console.log(error);
            });
    }

    getBasketTotal() {
        var userToken = localStorage.getItem('userToken');

        let query = `{
            me {
                cart {
                    cart_total
                }
            }
        }`;

        return this.graphqlService
            .sendQuery(query, userToken)
            .then(re => {
                let currency = localStorage.getItem('currency');

                // get the default currency from the shopConfig
                if (!currency) {
                    currency = localStorage.getItem('default_currency');
                }

                $('#checkout-cart-total').html(`${re.data.me.cart.cart_total / 100} ${currency}`);
                return re;
            })
            .catch(error => {
                // console.log(error);
            });
    }

    displayLineItems() {
        return this.cart.viewProductsInCart().then(re => {
            try {
                // See if there is an error
                let error = re.errors[0].debugMessage;
                // console.log(error);
            } catch {
                let items = re.data.me.cart.items;

                if (items && items.length > 0) {
                    this.cart.lookupProductsInCart(items).then(re => {
                        let currency = localStorage.getItem('currency');

                        // get the default currency from the shopConfig
                        if (!currency) {
                            currency = localStorage.getItem('default_currency');
                        }

                        re.forEach(element => {
                            $('#table-body-line-item').append(
                                $(`<tr>
                                    <td>${element.title}</td>
                                    <td>${element.quantity}</td>
                                    <td>${element.price_cents / 100} ${currency}</td>
                                </tr>`),
                            );
                        });
                    });
                } else {
                    $('.checkout-container').html(errorTemplate('No products in cart.'));
                }
            }

            return re;
        });
    }
}
export { Checkout };
