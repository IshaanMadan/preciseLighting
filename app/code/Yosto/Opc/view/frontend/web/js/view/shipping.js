/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define([
    'Magento_Checkout/js/view/shipping',
    'uiRegistry',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/action/select-shipping-address',
    "ko"
], function(
    Shipping,
    registry,
    setShippingInformationAction,
    selectShippingMethodAction,
    checkoutData,
    quote,
    addressConverter,
    selectShippingAddress,
    ko
) {
    self.flag = false;
    self.previousAmount = -1;
    return Shipping.extend({
        isSelected: ko.computed(function () {
            if (quote.shippingMethod()) {
                if (!self.flag) {
                    setShippingInformationAction();
                    self.flag = true;
                }

                if (self.previousAmount != -1 && self.previousAmount != quote.shippingMethod().amount) {
                    setShippingInformationAction();
                }
                self.previousAmount = quote.shippingMethod().amount;
                return quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'];
            }
            return null;
        }),
        /**
         * @param {Object} shippingMethod
         * @return {Boolean}
         */
        selectShippingMethod: function (shippingMethod) {
            selectShippingMethodAction(shippingMethod);
            setShippingInformationAction();
            checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);
            self.flag = false;
            self.previousAmount = shippingMethod.amount;
            return true;
        }
    });
});
