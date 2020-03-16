<?php

namespace Tests\Feature;

use Gernzy\Server\Classes\BarBeforeCheckout;
use Gernzy\Server\Classes\FooBeforeCheckout;
use Gernzy\Server\Classes\StripeBeforeCheckout;
use Gernzy\Server\Listeners\BeforeCheckout;
use Gernzy\Server\Services\EventService;
use Gernzy\Server\Testing\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class GernzyHookSystemTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Actions listen to an Event, so when an event is triggered, an Action that is listening
     * to that event will also trigger. The third party developer will register Actions for an Event. These Actions will Listen
     * for the Event to fire and then execute.
     *
     * @return void
     */
    public function testEventService()
    {
        // Set actions for event at run time, for testing purposes
        config(['events.' . BeforeCheckout::class => [StripeBeforeCheckout::class, FooBeforeCheckout::class, BarBeforeCheckout::class]]);

        // Trigger the event somewhere in code through EventService
        $eventService = EventService::triggerEvent(BeforeCheckout::class);

        // All the actions that we're called
        $actions = $eventService->getMeta();

        // Prepare array for essertContains (flatten array)
        $actions = array_column($actions, 'action');

        $this->assertContains(StripeBeforeCheckout::class, $actions, "actionsArray doesn't contain StripeBeforeCheckout");
        $this->assertContains(FooBeforeCheckout::class, $actions, "actionsArray doesn't contain FooBeforeCheckout");
        $this->assertContains(BarBeforeCheckout::class, $actions, "actionsArray doesn't contain BarBeforeCheckout");
    }

    public function testEventServiceWithData()
    {
        $checkoutData = [
            "name" => "Luke",
            "email" => "cart@example.com",
            "telephone" => "082456748",
            "mobile" => "08357684758",
            "billing_address" => [
                "line_1" => "1 London Way",
                "line_2" => "",
                "state" => "London",
                "postcode" => "SW1A 1AA",
                "country" => "UK"
            ],
            "shipping_address" => [
                "line_1" => "1 London Way",
                "line_2" => "",
                "state" => "London",
                "postcode" => "SW1A 1AA",
                "country" => "UK"
            ],
            "use_shipping_for_billing" => true,
            "payment_method" => "",
            "agree_to_terms" => true,
            "notes" => ""
        ];

        // Set actions for event at run time, for testing purposes
        config(['events.' . BeforeCheckout::class => [StripeBeforeCheckout::class, FooBeforeCheckout::class, BarBeforeCheckout::class]]);

        // Trigger the event somewhere in code through EventService
        $eventService = EventService::triggerEvent(BeforeCheckout::class, $checkoutData);

        $historyOfModifiedData = $eventService->getModifiedData();

        $this->assertNotEmpty($historyOfModifiedData);
    }
}
