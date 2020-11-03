<?php

namespace  Gernzy\Server\Packages\Paypal\Services;

use \App;
use Gernzy\Server\Classes\ActionClassPaymentHistory;
use Gernzy\Server\Exceptions\GernzyException;
use Gernzy\Server\Models\OrderTransaction;
use Gernzy\Server\Services\PaymentProviderInterface;
use Illuminate\Support\Facades\Log;

class PaypalService implements PaypalServiceInterface, PaymentProviderInterface
{
    public function capturePayment($orderID)
    {
        $captureOrderPaypal = App::make('Paypal\CaptureOrderPaypal');
        if (!$captureResponse = $captureOrderPaypal->captureOrder($orderID, false)) {
            Log::error("Error on paypal response: {$captureResponse}\n", ['package' => "Paypal"]);
            return response()->json(['error' => 'Server error'], 400);
        }

        // Find the order transaction data
        $orderTransaction = OrderTransaction::where('transaction_data->paypal_data->result->id', $captureResponse->result->id)->first();

        if (!isset($orderTransaction)) {
            Log::error("The transaction order data was not found for that successful payment in Paypal: {$captureResponse->result->id}\n", ['package' => "Paypal"]);
            throw new GernzyException(
                'The transaction order data was not found for the successful payment in Paypal.',
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

    public function createOrder($debug, $cartTotal, $sessionCurrency)
    {
        $createOrderPaypal = App::make('Paypal\CreateOrderPaypal');
        $response = $createOrderPaypal->createOrder($debug, $cartTotal, $sessionCurrency);
        return $response;
    }


    public function providerName()
    {
        return 'Paypal';
    }


    public function logFile()
    {
        return '../paypalLog.txt';
    }

    // $provider, $status, $amount, $date
    public function getTransactionHistory($orderTransactionId): ActionClassPaymentHistory
    {
        $orderTransaction = OrderTransaction::find($orderTransactionId);

        $provider = $orderTransaction->payment_method;
        $status = $orderTransaction->status;
        $amount = $orderTransaction->transaction_data["paypal_data"]["result"]["purchase_units"][0]["amount"];
        $date = $orderTransaction->transaction_data["paypal_data"]["result"]["create_time"];

        $returnObj = new ActionClassPaymentHistory($provider, $status, $amount, $date);

        return $returnObj;
    }
}
