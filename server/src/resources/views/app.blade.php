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
    <script src="https://js.stripe.com/v3/"></script>


</head>

<body>
    <div id="app">

        <div uk-sticky="media: 960" class="uk-navbar-container tm-navbar-container uk-sticky uk-sticky-fixed uk-background-primary" style="position: fixed; top: 0px; width: 1130px;">
            <div class="uk-container uk-container-expand">
                <nav class="uk-navbar">
                    <div class="uk-navbar-left">
                        <a href="#offcanvas-slide" class="uk-button uk-button-default" uk-toggle>Menu</a>
                        <div id="offcanvas-slide" uk-offcanvas>
                            <div class="uk-offcanvas-bar">

                                <ul class="uk-nav uk-nav-default">
                                    <li class="uk-active"><a href="#">Active</a></li>
                                    <li><a href="#">Item</a></li>
                                    <li class="uk-nav-header">Header</li>
                                    <li><a href="#">Item</a></li>
                                    <li><a href="#">Item</a></li>
                                    <li class="uk-nav-divider"></li>
                                    <li><a href="#">Item</a></li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="uk-navbar-right">
                        <ul class="uk-navbar-nav uk-visible">
                            <li><a href="/shop">Home</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Clearance</a></li>
                        </ul>
                        <div class="uk-inline">
                            <button class="uk-button uk-button-default" type="button" uk-icon="icon: chevron-down">Currency</button>
                            <div uk-dropdown x-data="availableCurrencies()" x-init="fetch()">
                                <ul id="available-currencies" class="uk-nav uk-dropdown-nav">
                                    <template x-for="currency in currencies" :key="currency">
                                        <li><a href='#' class='available-currency' :data-currency="currency" x-text="currency" x-on:click="changeCurrencyClick"></a></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                        <div class="uk-navbar-item">
                            <a href="/cart" class="uk-button uk-button-default tm-button-default uk-icon cart-button" uk-icon="icon: cart">Cart </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

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
    <script src="/js/gernzy.js"></script>
</body>

</html>