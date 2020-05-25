<?php

namespace  Gernzy\Server\Packages\Paypal\Services;

use \App;
use Gernzy\Server\Exceptions\GernzyException;
use Gernzy\Server\Models\OrderTransaction;
use Illuminate\Support\Facades\Log;

class PaypalService implements PaypalServiceInterface
{
    public function capturePayment($orderID)
    {
        $captureOrderPaypal = App::make('Paypal\CaptureOrderPaypal');
        if (!$captureResponse = $captureOrderPaypal->captureOrder($orderID, false)) {
            return response()->json(['error' => 'Server error'], 400);
        }

        // Find the order transaction data
        $orderTransaction = OrderTransaction::where('transaction_data->paypal_data->result->id', $captureResponse->result->id)->first();

        if (!isset($orderTransaction)) {
            Log::error('The transaction order data was not found for that successful payment.' + $captureResponse->result->id);
            throw new GernzyException(
                'The transaction order data was not found for that successful payment.',
                ''
            );
        }

        /** Check if the order status is already set to paid
         * and if not then continue with the flow
         */
        if ($orderTransaction->status === 'paid') {
            return;
        }

        // Update the status of the order transaction data to paid
        $orderTransaction->status = 'paid';

        // Remove the secret from event as it will be save in the database
        if (isset($captureResponse->data->object->client_secret)) {
            $captureResponse->data->object->client_secret = null;
        }

        // Add the stripe event data to the json column of transaction_data table
        $transaction_data = $orderTransaction->transaction_data;
        $transaction_data['paypal_payment_capture'] = $captureResponse;

        $orderTransaction->transaction_data = $transaction_data;
        $orderTransaction->save();

        return $captureResponse;
    }

    public function createOrder($debug = false, $cartTotal, $sessionCurrency)
    {
        $createOrderPaypal = App::make('Paypal\CreateOrderPaypal');
        $response = $createOrderPaypal->createOrder($debug, $cartTotal, $sessionCurrency);
        return $response;
    }
}
