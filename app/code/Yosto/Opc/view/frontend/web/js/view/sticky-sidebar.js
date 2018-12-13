/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/view/sidebar',
    'Yosto_Opc/js/custom-sticky'
], function($, Component, domReady) {
    'use strict';

    return Component.extend({
        initialize: function() {
            this.stick();
        },

        stick: function() {
            var yostoOpcSidebar = $('.opc-summary-wrapper.opc-sidebar');
            var yostoLinkedProductsSidebar = $('#yosto-checkout-linked-product.opc-sidebar');
            if (!yostoOpcSidebar.length) {
                setTimeout(this.stick.bind(this), 1000);
                return false;
            } else {

                this.sticky = yostoOpcSidebar.stickySummary({
                    container: '.yosto-opc #checkout',
                    offsetTop: 25
                });
                yostoOpcSidebar.addClass('opc-sidebar-sticky')
            }
            /**
            if (!yostoLinkedProductsSidebar.length) {
                setTimeout(this.stick.bind(this), 1000);
                return false;
            } else {

                this.sticky = yostoLinkedProductsSidebar.stickySummary({
                    container: '.yosto-opc #checkout',
                    offsetTop: 25
                });
            }**/
        }
    });
});
