define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/resource-url-manager',
    'mage/storage',
    'Yosto_Opc/js/model/validator',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Checkout/js/model/full-screen-loader',
    'Yosto_Opc/js/action/save-additional-data'
], function(
    $,
    quote,
    resourceUrlManager,
    storage,
    validator,
    errorProcessor,
    fullScreenLoader,
    saveAdditionalData
) {
    'use strict';

    return {
        placeOrder: function() {
            if (!validator.validate()) {
                // scroll to error if it's not visible in viewport
                validator.scrollToError();
                return;
            }

            // 1. Save shipping info
            if (!quote.isVirtual()) {
                this.submitShippingInformation(
                    this.submitPaymentInformation.bind(this)
                );
            } else {
                this.submitPaymentInformation();
            }
        },
        submitShippingInformation: function(callback) {
            var extensionAttributes = {};
            if (window.checkoutConfig.yosto_storepickup && window.checkoutConfig.yosto_storepickup.active == 1) {
                extensionAttributes.pickup_date = $('[name="pickup_date"]').val();
                extensionAttributes.location_id = $('[name="location_id"]').val();
            }
            if (window.checkoutConfig.yosto_deliverydate && window.checkoutConfig.yosto_deliverydate.active == 1) {
                extensionAttributes.delivery_date = $('[name="delivery_date"]').val();
                extensionAttributes.delivery_comment =  $('[name="delivery_comment"]').val();
            }

            var payload = {
                addressInformation: {
                    shipping_address: quote.shippingAddress(),
                    billing_address: quote.billingAddress(),
                    shipping_method_code: quote.shippingMethod().method_code,
                    shipping_carrier_code: quote.shippingMethod().carrier_code,
                    extension_attributes: extensionAttributes
                }
            };

            fullScreenLoader.startLoader();

            storage.post(
                resourceUrlManager.getUrlForSetShippingInformation(quote),
                JSON.stringify(payload)
            )
                .done(function() {
                    fullScreenLoader.stopLoader();
                    callback();
                })
                .fail(function (response) {
                    fullScreenLoader.stopLoader();
                    errorProcessor.process(response);
                });
        },

        submitPaymentInformation: function() {
            saveAdditionalData();
            $('.checkout-agreements input:checkbox').prop('checked', true);
            $('.action.checkout', '.payment-method._active').click();
        }
    };
});
