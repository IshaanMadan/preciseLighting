/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define([
    'Magento_GiftMessage/js/view/gift-message',
    'Yosto_Opc/js/action/gift-options'
], function (Component, giftOptionsService) {
    return Component.extend({
        /**
         * Delete options
         */
        deleteOptions: function () {
            var result = giftOptionsService(this.model, true);
            this.model.getObservable('message')(null);
            this.model.getObservable('sender')(null);
            this.model.getObservable('recipient')(null);
            this.resultBlockVisibility(false);
            return result;
        },
        /**
         * Submit options
         */
        submitOptions: function () {
            var result = giftOptionsService(this.model);
            this.model.getObservable('alreadyAdded')(true);
            this.toggleFormBlockVisibility();
            return result;
        }
    })
});