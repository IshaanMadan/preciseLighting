/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/mage'
], function ($) {
    'use strict';

    return function (config, element) {
        $(element).mage('validation', {
            errorPlacement: function (error, element) {
                var parent = element.parents('.field-block');
                if (parent.length) {
                    parent.after(error);
                } else {
                    if (element.parents('.review-control-vote').length) {
                        parent = element.parents('.review-control-vote');
                        parent.after(error);
                    } else {
                        element.after(error);
                    }
                }
            }
        });
    };
});