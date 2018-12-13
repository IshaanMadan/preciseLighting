/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
/*global define,alert*/
define(
    [
        'ko',
        'Magento_Catalog/js/price-utils'
    ],
    function (ko, priceUtils) {
        'use strict';
        var giftWrapAmount = ko.observable(window.checkoutConfig.yosto_opc.giftwrap_amount);
        var hasWrap = ko.observable(window.checkoutConfig.yosto_opc.has_giftwrap);
        return {
            giftWrapAmount: giftWrapAmount,
            hasWrap: hasWrap,

            getGiftWrapAmount: function() {
                return this.giftWrapAmount();
            },
            
            getIsWrap: function () {
                return this.hasWrap();
            },

            setGiftWrapAmount: function (amount) {
                this.giftWrapAmount(amount);
            },

            setIsWrap: function (isWrap) {
                return this.hasWrap(isWrap);
            }
        };
    }
);
