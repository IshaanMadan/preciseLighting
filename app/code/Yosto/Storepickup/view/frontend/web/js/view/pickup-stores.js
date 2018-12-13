define([
    'jquery',
    'ko',
    'uiComponent',
    'Yosto_Opc/js/google_maps_loader',
    'Magento_Ui/js/modal/modal'
], function ($, ko, Component, GoogleMapsLoader, modal) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Yosto_Storepickup/pickup-stores'
        },
        initialize: function () {
            this._super();
            this.visible = (window.checkoutConfig.yosto_storepickup.active == 1);
            this.stores = window.checkoutConfig.yosto_storepickup.stores;
            return this;
        },
        openPopup: function() {
            var options = {
                type : 'popup',
                responsive : true,
                innerScroll : false,
                title : 'Store Locations'
            };

            var popupElement = $('#stores-pickup-popup');
            var popup = modal(options, popupElement);
            popupElement.modal("openModal");
            $('.modal-footer').hide();
            //var currentStore = $('#location_id option:selected').data('coordinate');
            $('#store_select').trigger('change');
                //.val('' + currentStore)

        }
    });
});
