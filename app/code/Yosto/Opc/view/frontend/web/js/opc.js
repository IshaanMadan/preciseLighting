define([
    'jquery',
    'uiComponent',
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Yosto_Opc/js/model/validator',
    'Magento_Checkout/js/action/select-billing-address'
], function($, Component, registry, quote, validator, selectBillingAddress) {
    'use strict';

    return Component.extend({
        sameAsShippingObservers: [],
        isFirstSelectPaymentMethod: true,
        initialize: function() {
            if (quote.isVirtual()) {
                $('body').removeClass('yosto-opc-col-2')
                    .removeClass('yosto-opc-col-1')
                    .removeClass('yosto-opc-col-3')
                    .addClass('yosto-opc-quote-virtual');
            }

            quote.shippingMethod.subscribe(function() {
                validator.removeNotice('#co-shipping-method-form');
            });
            quote.paymentMethod.subscribe(function(method) {
                validator.removeNotice('#co-payment-form');
                if (this.isFirstSelectPaymentMethod) {
                    $('#billing-address-same-as-shipping-' + method.method).click();
                    this.isFirstSelectPaymentMethod = false;
                }
                // toggle billing address details section
                this.syncBillingAddressDetailsVisibility(method);
                this.addSameAsShippingObserver(method);
            }, this);

            // Instant billing address update
            quote.shippingAddress.subscribe(function(address) {
                var method = quote.paymentMethod();
                if (!method) {
                    return;
                }

                registry.get(
                    'checkout.steps.billing-step.payment.payments-list.' + method.method + '-form',
                    function(billingAddress) {
                        if (billingAddress.isAddressSameAsShipping()) {
                            selectBillingAddress(quote.shippingAddress());
                        }
                    }
                );
            }, this);
        },

        /**
         * @return boolean
         */
        syncBillingAddressDetailsVisibility: function(method) {
            method = method || quote.paymentMethod();
            if (!method) {
                return;
            }
            registry.get(
                'checkout.steps.billing-step.payment.payments-list.' + method.method + '-form',
                function(billingAddress) {
                    var flag = billingAddress.isAddressSameAsShipping();
                    $('body').toggleClass('equal-billing-shipping', flag);
                }
            );
            return true;
        },

        /**
         * @param void
         */
        addSameAsShippingObserver: function(method) {
            method = method || quote.paymentMethod();
            if (!method || this.sameAsShippingObservers.indexOf(method.method) >= 0) {
                return;
            }
            this.sameAsShippingObservers.push(method.method);
            registry.get(
                'checkout.steps.billing-step.payment.payments-list.' + method.method + '-form',
                function(billingAddress) {
                    billingAddress.isAddressSameAsShipping.subscribe(function(flag) {
                        $('body').toggleClass('equal-billing-shipping', flag);
                    });
                }
            );
        }
    });
});
