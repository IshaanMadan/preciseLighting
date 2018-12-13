/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    if (!$.hasOwnProperty('mageWorkshop')) {
        $.mageWorkshop = {};
    }

    $.widget('mageWorkshop.mageWorkshop_detailedReview_reviewRating', {
        options: {
            addReviewButton: '.review-average-info a.add,.product-reviews-summary a.add',
            allReviewBlock: '#customer-reviews',
            filtersReviewBlock: '#review-search-form',
            createReviewForm: '#review-form',
            backButton: '.button-back'
        },

        _create: function () {
            $(this.options.createReviewForm).hide();
            $(this.options.addReviewButton).click(this.createReview.bind(this));
        },

        createReview: function (event) {
            event.preventDefault();
            $(this.options.allReviewBlock).hide();
            $(this.options.filtersReviewBlock).hide();
            $(this.element.parent()).hide();
            $(this.options.createReviewForm).show();
        }
    });

    return $.mageWorkshop.mageWorkshop_detailedReview_reviewRating;
});