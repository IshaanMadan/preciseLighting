/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'ko',
    'Magento_Customer/js/customer-data',
    'jquery',
    'sidebar'
], function (Component, ko) {
    'use strict';
    return Component.extend({
        itemRenderer: [],

        isLoading: ko.observable(false),

        getItemRenderer: function (type) {
            return this.itemRenderer[type] || 'defaultRenderer';
        }
    });
});
