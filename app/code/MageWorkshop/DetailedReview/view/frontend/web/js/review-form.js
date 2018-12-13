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

    $.widget('mageWorkshop.mageWorkshop_detailedReview_reviewForm', {
        options: {
            reviewForm: '#review-form',
            multiSelect: '.field-block select[multiple="multiple"]',
            $productInfo: $('.items[role="tablist"]'),
            allReviewBlock: '#customer-reviews',
            filtersReviewBlock: '#review-search-form',
            createReviewForm: '#review-form',
            containerReviewRating: '.container-review-rating',
            backButton: '.button-back'
        },

        _create: function () {
            if ($(this.options.filtersReviewBlock).length) {
                $(this.options.createReviewForm).on('click', this.options.backButton, this.backButton.bind(this));
            } else {
                $(this.options.backButton).hide();
            }
            $(this.options.reviewForm).on('change', this.options.multiSelect, this.checkMultiSelect.bind(this));
        },

        checkMultiSelect: function (event) {
            var that = this,
                $selectedOptions = $(event.currentTarget.selectedOptions),
                currentSelectName = $(event.currentTarget.name).selector,
                arraySelectOptions = [];

            $selectedOptions.each(function (index, element) {
                if (typeof (element) !== 'undefined') {
                    arraySelectOptions.push(element.label);
                    $(that.element.find('.field-block select[multiple="multiple"]')).each(function (index, element) {
                        if (element.name !== currentSelectName) {
                            $(element.options).each(function (index, option) {
                                option.disabled = arraySelectOptions.indexOf(option.label) !== -1;
                            })
                        }
                    });
                }
            });
        },

        backButton: function (event) {
            event.preventDefault();
            $(this.options.allReviewBlock).show();
            $(this.options.filtersReviewBlock).show();
            $(this.options.containerReviewRating).show();
            $(this.element.parent()).show();
            $(this.options.createReviewForm).hide();
        }
    });

    return $.mageWorkshop.mageWorkshop_detailedReview_reviewForm;
});
