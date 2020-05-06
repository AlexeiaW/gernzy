<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.2.6/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.2.6/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.2.6/dist/js/uikit-icons.min.js"></script>

    <!-- Stripe -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->

</head>

<body>
    <script src="https://www.paypal.com/sdk/js?client-id=AaPIMFArYm6R5VZgKCqAeh9bTTf1g3JA8zeiWz62xPa1niqDcHk4dQVWjJLz_R3RVPraZEnT1uossdfE">
        // Required. Replace SB_CLIENT_ID with your sandbox client ID.
    </script>

    <div id="paypal">
        <main>
            <span id="loadingDiv" uk-spinner="ratio: 3" class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-flex-middle" style="z-index: 99999;
                    display: -webkit-box;
                    -webkit-box-align: center;
                    -webkit-box-pack: center;

                    position: fixed;
                    align-items: center;
                    justify-content: center;
                    display: flex;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    right: 0;">
            </span>
            @yield('content')
        </main>

    </div>

    <script>
        paypal.Buttons({
            createOrder: function() {
                return fetch('/create-paypal-transaction', {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json'
                    }
                }).then(function(res) {
                    console.log('hi' + JSON.stringify(res));
                    return res.json();
                }).then(function(data) {
                    return data.orderID; // Use the same key name for order ID on the client and server
                });
            },
            // onApprove: function(data, actions) {
            //     // This function captures the funds from the transaction.
            //     return actions.order.capture().then(function(details) {
            //         // This function shows a transaction success message to your buyer.
            //         alert('Transaction completed by ' + details.payer.name.given_name);
            //     });
            // }
        }).render('#paypal-button-container');
        //This function displays Smart Payment Buttons on your web page.
    </script>

    <script src="/js/gernzy.js"></script>
</body>

</html>