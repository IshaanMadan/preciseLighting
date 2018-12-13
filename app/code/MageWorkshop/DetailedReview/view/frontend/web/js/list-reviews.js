/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery'
], function ($) {
    'use strict';

    if (!$.hasOwnProperty('mageWorkshop')) {
        $.mageWorkshop = {};
    }

    $.widget('mageWorkshop.mageWorkshop_detailedReview_listReviews', {
        _create: function () {
            $(".moreLink").toggle(function () {
                $(this).text($(this).data('less'))
                    .siblings(".completeDescription")
                    .show();
                $(this).siblings(".teaser")
                    .hide();
            }, function () {
                $(this).text($(this).data('more'))
                    .siblings(".completeDescription")
                    .hide();
                $(this).siblings(".teaser")
                    .show();
            });
        }
    });

    return $.mageWorkshop.mageWorkshop_detailedReview_listReviews;
});