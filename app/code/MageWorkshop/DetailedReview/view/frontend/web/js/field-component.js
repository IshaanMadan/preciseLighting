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
], function (Component, ko, customerData, $) {
    'use strict';
    return Component.extend({
        isLoading: ko.observable(true),

        initialize: function () {
            this._super();
            this.review = customerData.get('review').extend({disposableCustomerData: 'review'});
        },
        /**
         * @param {string} rules
         * @param {string} frontendInput
         */
        getValidRules: function (rules, frontendInput) {
            var inputType = this.displayArea;
            if (inputType == frontendInput) {
                return JSON.stringify(rules);
            }
        },
        getCustomerNickname: function () {
            return this.review().nickname || customerData.get('customer')().firstname;
        },

        getAttributeWidth: function (d, t, m) {
            var size = '';
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    size = m;
                } else if ($(window).width() < 1024) {
                    size = t;
                } else {
                    size = d;
                }
            });

            $(document).ready(function() {
                $(window).resize()
            });

            return size + '%';
        },

        getAttributeHorizontalLine: function (v) {
            if(v === '1') {
                return 'enableHorizontalLine';
            }
           return 'disableHorizontalLine';
        },

        setLastFieldInLine: function (v) {
            if(v === '1') {
                return 'field-block lastFieldInline';
            }
            return 'field-block moreFieldInLine';
        }
    });
});
