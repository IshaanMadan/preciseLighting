define([
    'jquery',
    'ko',
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/payment/additional-validators',
    'mage/translate'
], function($, ko, registry, quote, additionalValidators, $t) {
    'use strict';

    return {
        validate: function() {
            var result = true;

            // 1. Validate selected shipping and payment radio buttons
            var isShippingSelected = this.validateShippingRadios(),
                isPaymentSelected  = this.validatePaymentRadios();
            var isOrderAttachmentRequired = this.validateOrderAttachment();
            if (!isShippingSelected || !isPaymentSelected || !isOrderAttachmentRequired) {
                return false;
            }

            // 2. Validate shipping information
            if (!quote.isVirtual()) {
                registry.get(
                    'checkout.steps.shipping-step.shippingAddress',
                    function(shippingAddress) {
                        if (!shippingAddress.validateShippingInformation()) {
                            result = false;
                        }
                    }
                );
            }

            // 3. Validate payment information
            registry.get(
                'checkout.steps.billing-step.payment.payments-list.' + quote.paymentMethod().method,
                function(payment) {
                    if (!payment.validate() || !additionalValidators.validate()) {
                        result = false;
                    }
                }
            );

            return result;
        },

        isElementVisibleInViewport: function(el) {
            var rect = el.getBoundingClientRect(),
                viewport = {
                    width: $(window).width(),
                    height: $(window).height()
                };

            return (
                rect.top  >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= viewport.height &&
                rect.right  <= viewport.width
            );
        },

        /**
         * Scroll to error if it's not visible in viewport
         */
        scrollToError: function() {
            var messages = $('div.mage-error:visible, .yosto-opc-msg:visible');
            if (!messages.length) {
                return;
            }

            var timeout = 0,
                visibleMessage = messages.toArray().find(this.isElementVisibleInViewport);

            if (!visibleMessage) {
                visibleMessage = messages.first();
                timeout = 500;
                $('html, body').animate({
                    scrollTop: visibleMessage.offset().top - 70
                }, timeout);
            }
        },

        /**
         * Check is shipping radio is selected
         */
        validateShippingRadios: function() {
            var el = $('#co-shipping-method-form');
            if (!el.length) {
                return true;
            }

            this.removeNotice(el);
            if (!quote.shippingMethod() || typeof quote.shippingMethod() !== 'object') {
                this.addNotice(el, $t('Please specify a shipping method.'));
                return false;
            }
            return true;
        },

        /**
         * Check is payment radio is selected
         */
        validatePaymentRadios: function() {
            var el = $('#co-payment-form');
            if (!el.length) {
                return true;
            }

            this.removeNotice(el);
            if (!quote.paymentMethod() || typeof quote.paymentMethod() !== 'object') {
                this.addNotice(el, $t('Please specify a payment method.'));
                return false;
            }
            return true;
        },
        /**
         * Validate order attachment if it is required
         */
        validateOrderAttachment: function() {
            var el = $('#yosto-order-attachment');
            if (!el.length) {
                return true;
            }

            this.removeNotice(el);
            var fileUploadStatus  = window.checkoutConfig.yosto_order_attachment.file_upload_status;
            var isRequire = window.checkoutConfig.yosto_order_attachment.is_required;
            if ( !fileUploadStatus && isRequire == 1) {
                this.addNotice(el, $t('Please upload a file.'));
                return false;
            }
            return true;
        },

        /**
         * Add notice message at the top of the element
         *
         * @param el
         * @param msg
         */
        addNotice: function(el, msg) {
            el.prepend(
                '<div class="yosto-opc-msg message notice"><span>' +
                msg +
                '</span></div>'
            );
        },

        /**
         * Remove notice label
         *
         * @param  el
         * @return void
         */
        removeNotice: function(el) {
            $('.yosto-opc-msg', el).remove();
        }
    };
});
