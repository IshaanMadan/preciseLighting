/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Yosto_Opc/js/model/opc'
], function($, ko, Component, quote, opc) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Yosto_Opc/place-order'
        },
        message: ko.observable(false),

        placeOrder: opc.placeOrder.bind(opc),

        initialize: function() {
            this._super();
        }
    });
});
