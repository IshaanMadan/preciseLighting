/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
/*global define*/
define(
    [
        'jquery',
        'uiComponent',
        'ko',
        'mage/storage',
        'Magento_Catalog/js/price-utils',
        'Yosto_Opc/js/view/summary/gift-wrap',
        'Yosto_Opc/js/action/reload-shipping-method',
        'Magento_Checkout/js/action/get-payment-information',
        'Yosto_Opc/js/model/gift-wrap',
        'Magento_Checkout/js/model/totals'
    ],
    function($, Component, ko, storage, priceUtils, giftWrap, reloadShippingMethod, getPaymentInformation, giftWrapModel, totals) {
        'use strict';
        return Component.extend({
            initialize: function () {
                this._super();
                var self = this;
                this.giftWrapAmountPrice = ko.computed(function () {
                    var priceFormat = window.checkoutConfig.priceFormat;
                    return priceUtils.formatPrice(self.giftWrapValue(), priceFormat)
                });
            },

            isGiftWrap: ko.observable(window.checkoutConfig.yosto_opc.enable_giftwrap),

            giftWrapValue: ko.computed(function () {
                return giftWrapModel.getGiftWrapAmount();
            }),
       
            defaults: {
                template: 'Yosto_Opc/gift-wrap'
            },

            formatPrice: function(amount) {
                amount = parseFloat(amount);
                var priceFormat = window.checkoutConfig.priceFormat;
                return priceUtils.formatPrice(amount, priceFormat)
            },

            setGiftWrapValue: function (amount) {
                this.giftWrapValue(amount);
            },
            addGiftWrap: function () {
                var params = {
                    isChecked: !this.isChecked()
                };
                var self = this;
                storage.post(
                    'yosto_opc/index/giftwrapProcess',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                        window.checkoutConfig.yosto_opc.giftwrap_amount = result;
                        reloadShippingMethod();
                        totals.isLoading(true);
                        getPaymentInformation().done(function () {
                            if (self.isChecked()) {
                                giftWrapModel.setGiftWrapAmount(result);
                                giftWrapModel.setIsWrap(true);
                            } else {
                                giftWrapModel.setIsWrap(false);
                            }
                            totals.isLoading(false);
                        });
                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                    }
                );
                return true;
            },

            isChecked: ko.observable(window.checkoutConfig.yosto_opc.has_giftwrap)
            
        });
    }
);
