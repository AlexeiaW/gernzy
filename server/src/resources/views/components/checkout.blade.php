<!-- checkout component -->
<form id="checkout-form" class="uk-card uk-card-default uk-card-body uk-card-large" x-data="checkoutForm()" x-init="initValues()">
    <h1 class="uk-heading-small">Complete checkout</h1>
    <fieldset class=" uk-fieldset">

        <legend class="uk-legend">Your details</legend>

        <div class="uk-margin">
            <input x-model="values.name" name="name" class="uk-input uk-box-shadow-hover-small uk-box-shadow-hover-small" type="text" placeholder="Name" required autocomplete="name">
        </div>
        <div class="uk-margin">
            <input x-model="values.email" name="email" class="uk-input uk-box-shadow-hover-small" type="email" placeholder="Email" required autocomplete="email">
        </div>
        <div class="uk-margin">
            <input x-model="values.telephone" name="telephone" class="uk-input uk-box-shadow-hover-small" type="tel" placeholder="Mobile" required autocomplete="tel">
        </div>
        <div class="uk-margin">
            <input x-model="values.mobile" name="mobile" class="uk-input uk-box-shadow-hover-small" type="tel" placeholder="Telephone" required autocomplete="tel">
        </div>

        <div class="uk-card uk-card-default uk-card-body">
            <label class="uk-form-label" for="form-horizontal-text">Shipping Address</label>
            <div class="uk-margin">
                <input x-model="values.shipping_address_line_1" class="uk-input uk-box-shadow-hover-small" name="shipping_address_line_1" id="frmAddressSL1" placeholder="123 Any Street" required autocomplete="shipping address-line1">
            </div>
            <div class="uk-margin">
                <input x-model="values.shipping_address_line_2" class="uk-input uk-box-shadow-hover-small" name="shipping_address_line_2" id="frmAddressSL2" placeholder="123 Any Street" autocomplete="shipping address-line2">
            </div>
            <div class="uk-margin">
                <input x-model="values.shipping_address_state" class="uk-input uk-box-shadow-hover-small" name="shipping_address_state" id="frmAddressState" placeholder="State" required autocomplete="shipping address-level1">
            </div>
            <div class="uk-margin">
                <input x-model="values.shipping_address_postcode" class="uk-input uk-box-shadow-hover-small" name="shipping_address_postcode" id="frmAddressPostal" placeholder="Postal code" autocomplete="shipping postal-code">
            </div>
            <div class="uk-margin">
                <input x-model="values.shipping_address_country" class="uk-input uk-box-shadow-hover-small" name="shipping_address_country" id="frmAddressCountry" placeholder="Country" required autocomplete="shipping country">
            </div>

            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                <label><input x-model="values.use_shipping_for_billing" id="use_shipping_for_billing" name="use_shipping_for_billing" class="uk-checkbox uk-box-shadow-hover-small" type="checkbox">Use shipping for billing address</label>
            </div>
        </div>

        <div id="billing-info" class="uk-card uk-card-default uk-card-body">
            <label class="uk-form-label" for="form-horizontal-text">Billing Address</label>
            <div class="uk-margin">
                <input x-model="values.billing_address_line_1" class="uk-input uk-box-shadow-hover-small" name="billing_address_line_1" id="frmAddressSBillL1" placeholder="123 Any Street" required autocomplete="address-line1">
            </div>
            <div class="uk-margin">
                <input x-model="values.billing_address_line_2" class="uk-input uk-box-shadow-hover-small" name="billing_address_line_2" id="frmAddressSBillL2" placeholder="123 Any Street" autocomplete="address-line2">
            </div>
            <div class="uk-margin">
                <input x-model="values.billing_address_state" class="uk-input uk-box-shadow-hover-small" name="billing_address_state" id="frmAddressStateBill" placeholder="State" required autocomplete="address-level1">
            </div>
            <div class="uk-margin">
                <input x-model="values.billing_address_postcode" class="uk-input uk-box-shadow-hover-small" name="billing_address_postcode" id="frmAddressPostalBill" placeholder="Postal code" autocomplete="postal-code">
            </div>
            <div class="uk-margin">
                <input x-model="values.billing_address_country" class="uk-input uk-box-shadow-hover-small" name="billing_address_country" id="frmAddressCountryBill" placeholder="Country" required autocomplete="country">
            </div>
        </div>

        <div class="uk-margin">
            <label class="uk-form-label" for="form-horizontal-text">Payment method</label>
            <select x-model="values.payment_method" id="checkout_payment_method" name="payment_method" class="uk-select" required>
                <template x-for="provider in paymentProviders" :key="provider">
                    <option :value="provider.ui_value" x-text="provider.ui_option"></option>
                </template>
            </select>
        </div>

        <div class="uk-margin">
            <textarea x-model="values.notes" name="notes" class="uk-textarea uk-box-shadow-hover-small" rows="5" placeholder="Notes"></textarea>
        </div>

        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
            <label><input x-model="values.agree_to_terms" id="agree_to_terms" name="agree_to_terms" class="uk-checkbox uk-box-shadow-hover-small" required type="checkbox">Agree to Terms</label>
        </div>
    </fieldset>

    <button type="submit" class="uk-button uk-button-default uk-box-shadow-hover-small" x-on:click="submitClick">Submit</button>
</form>