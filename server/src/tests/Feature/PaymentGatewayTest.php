<?php

namespace Gernzy\Server\Tests\Feature;

use Gernzy\Server\Listeners\BeforeCheckout;
use Gernzy\Server\Models\OrderTransaction;
use Gernzy\Server\Models\Product;
use Gernzy\Server\Packages\Stripe\Actions\StripeBeforeCheckout;
use Gernzy\Server\Packages\Stripe\Services\StripeService;
use Gernzy\Server\Packages\Stripe\Services\StripeServiceMock;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Unit\TestCheckoutTest;

class PaymentGatewayTest extends TestCheckoutTest
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->availableCount = 11;

        $this->productPricesArray = factory(Product::class, $this->availableCount)->create();
    }


    public function testPaymentGatewayProvider()
    {

        // Change the payment method property of the form submission data
        $vars = ['$payment_method' => 'stripe_standard'];
        $this->checkoutMutation = strtr($this->checkoutMutation, $vars);

        // 1. Register service provider (mocking)
        // $this->app->bind('Stripe\StripeService', StripeService::class);
        $this->app->bind('Stripe\StripeService', StripeServiceMock::class);

        // 2. Registe event mapping
        config(['events.' . BeforeCheckout::class => [StripeBeforeCheckout::class,]]);


        // Create session, Add product to cart,  Create checkout
        $this->testUserCanCheckoutWithItemsInCart();
        $this->assertNotEmpty($this->result);

        $eventData = json_decode($this->result['data']['checkout']['event_data']);

        // Check for stripe secret
        $this->assertNotEmpty($eventData[0]->data->stripe_secret);

        $orderTransaction = OrderTransaction::find(1);
        $this->assertNotEmpty($orderTransaction->transaction_data);
        $this->assertNotEmpty($orderTransaction->transaction_data['stripe_payment_intent']);
        $this->assertEquals($orderTransaction->status, 'pending');
        $this->assertEquals($orderTransaction->payment_method, 'stripe_standard');
    }

    public function testPaymentGatewayProviderWithDifferentCurrency()
    {
        // Change the payment method property of the form submission data
        $vars = ['$payment_method' => 'stripe_standard'];
        $this->checkoutMutation = strtr($this->checkoutMutation, $vars);

        // Set the session currency
        $query = '
                mutation {
                    setSessionCurrency(input: {
                        currency: "EUR"
                    }){
                        currency
                    }
                }
            ';

        $response = $this->graphQLWithSession($query);

        // 1. Register service provider (mocking)
        // $this->app->bind('Stripe\StripeService', StripeService::class);
        $this->app->bind('Stripe\StripeService', StripeServiceMock::class);

        // 2. Registe event mapping
        config(['events.' . BeforeCheckout::class => [StripeBeforeCheckout::class,]]);

        // Create session, Add product to cart,  Create checkout
        $response = $this->graphQLWithSession($this->addToCartMutation);
        $response = $this->graphQLWithSession($this->checkoutMutation);
        $response->assertDontSee('errors');

        $result = $response->decodeResponseJson();

        $this->assertNotEmpty($result);

        $eventData = json_decode($result['data']['checkout']['event_data']);

        // Check for stripe secret
        $this->assertNotEmpty($eventData[0]->data->stripe_secret);

        $orderTransaction = OrderTransaction::find(1);
        $this->assertNotEmpty($orderTransaction->transaction_data);
        $this->assertNotEmpty($orderTransaction->transaction_data['stripe_payment_intent']);
        $this->assertEquals($orderTransaction->status, 'pending');
        $this->assertEquals($orderTransaction->payment_method, 'stripe_standard');
    }
}
