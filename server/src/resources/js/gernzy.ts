import $ = require('jquery');
import 'reflect-metadata';
import { GernzyContainer } from './container/inversify.config';
import { TYPES } from './types/types';
import { StoreProducts } from './interfaces/products';
import { GernzySession } from './interfaces/session';
import { GernzyCart } from './interfaces/cart';
import { GernzyCheckout } from './interfaces/checkout';
import 'alpinejs';

export default {
    init: function (userConfig: Gernzy.GernzyConfig) {
        let config = {
            ...{
                apiUrl: 'http://laravel-gernzy.test/graphql',
            },
            ...userConfig,
        };

        let pathname: string = window.location.pathname;

        // New up instances for use
        const productObj = GernzyContainer.get<StoreProducts>(TYPES.StoreProducts);
        const sessionService = GernzyContainer.get<GernzySession>(TYPES.GernzySession);
        const cart = GernzyContainer.get<GernzyCart>(TYPES.GernzyCart);
        const checkout = GernzyContainer.get<GernzyCheckout>(TYPES.GernzyCheckout);

        // Session setup
        sessionService.setupSessionFactory(config.apiUrl);

        productObj.endpointUrl(config.apiUrl);
        productObj.productsComponent();

        if (pathname.includes('cart')) {
            cart.endpointUrl(config.apiUrl);
            cart.cartSetup();
        }

        if (pathname.includes('checkout')) {
            checkout.setupCheckoutFactory(config.apiUrl);
        }
    },
};
