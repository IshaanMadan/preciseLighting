/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define(
    [
        'ko',
        'uiComponent'
    ],
    function(ko, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Yosto_Opc/order-comment'
            },

            isShowComment: ko.observable(window.checkoutConfig.yosto_opc.show_order_comment)

        });
    }
);
