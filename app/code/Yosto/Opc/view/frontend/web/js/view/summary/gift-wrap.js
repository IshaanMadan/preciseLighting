/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Yosto_Opc/js/model/gift-wrap'
    ],
    function ($, ko, Component, giftWrap) {
        return Component.extend({
            getPureValue: ko.observable(window.checkoutConfig.yosto_opc.giftwrap_amount),

            initialize: function () {
                this._super();
                var self = this;
                this.isGiftWrapDisplay = ko.computed(function () {
                    return (giftWrap.getIsWrap());
                });
            },

            defaults: {
                template: 'Yosto_Opc/summary/gift-wrap'
            },


            getValue: function () {
                return this.getFormattedPrice(giftWrap.getGiftWrapAmount());
            }

        });
    }
);
