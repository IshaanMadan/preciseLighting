/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'jquery',
        'uiComponent',
        'mage/storage',
        'Magento_Customer/js/customer-data',
        'Magento_Checkout/js/action/get-totals',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/quote',
        'Yosto_Opc/js/action/reload-shipping-method',
        'Magento_Checkout/js/action/get-payment-information',
        'Yosto_Opc/js/model/gift-wrap',
        'Magento_Ui/js/modal/confirm',
        'Magento_Ui/js/modal/alert',
        'mage/translate',
        'Magento_Catalog/js/price-utils'
    ],
    function (
        $,
        Component,
        storage,
        customerData,
        getTotalsAction,
        totals,
        quote,
        reloadShippingMethod,
        getPaymentInformation,
        giftWrapModel,
        confirm,
        alertPopup,
        $t,
        priceUtils
    ) {
        "use strict";
        var mixin = {


            params: '',

            defaults: {
                template: 'Yosto_Opc/summary/item/details'
            },

            addQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty + 1);
            },

            minusQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty - 1);
            },

            updateNewQty: function (data) {
                this.updateQty(data.item_id, 'update', data.qty);
            },

            deleteItem: function (data) {
                var self = this;
                confirm({
                    content: $t('Do you want to remove the item from cart?'),
                    actions: {
                        confirm: function () {
                            self.updateQty(data.item_id, 'delete', '');
                        },
                        always: function (event) {
                            event.stopImmediatePropagation();
                        }
                    }
                });

            },
            updateQty: function (itemId, type, qty) {
                var params = {
                    itemId: itemId,
                    qty: qty,
                    updateType: type
                };
                var self = this;
                storage.post(
                    'yosto_opc/quote/update',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {
                        var miniCart = $('[data-block="minicart"]');
                        miniCart.trigger('contentLoading');
                        customerData.reload('cart', true);
                        miniCart.trigger('contentUpdated');
                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                        if (result.error) {
                            alertPopup({
                                content: $t(result.error),
                                autoOpen: true,
                                clickableOverlay: true,
                                focus: "",
                                actions: {
                                    always: function(){

                                    }
                                }
                            });
                        }

                        if(result.cartEmpty || result.is_virtual){
                            window.location.reload();
                        }else{
                            if (result.giftwrap_amount && !result.error) {
                                giftWrapModel.setGiftWrapAmount(result.giftwrap_amount);
                            }
                            reloadShippingMethod();
                            totals.isLoading(true);
                            getPaymentInformation().done(function () {
                                totals.isLoading(false);
                            });
                        }

                    }
                );
            }
        };
        return function (target) {
            return target.extend(mixin);
        };
    }
);
