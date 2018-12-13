/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
/*global define*/
define(
    [
        'ko',
        'uiComponent'
    ],
    function(ko, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Yosto_Opc/sign-up-newsletter'
            },

            isShowNewsletter: ko.observable(window.checkoutConfig.yosto_opc.show_newsletter),

            isChecked: ko.observable(window.checkoutConfig.yosto_opc.newsletter_default_checked)
        });
    }
);
