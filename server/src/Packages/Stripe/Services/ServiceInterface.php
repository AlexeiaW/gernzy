<?php

namespace  Gernzy\Server\Packages\Stripe\Services;

interface ServiceInterface
{
    /**
     * Return a secret from Stripe
     *
     * @param string
     */
    public function getSecret($paymentIntent);
    public function createPaymentIntent($amount, $currency);
}
