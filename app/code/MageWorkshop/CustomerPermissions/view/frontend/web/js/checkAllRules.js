/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mageWorkshop_detailedReview_reviewRating',
    'Magento_Review/js/process-reviews'
], function($) {

    return function(config, element) {
        $(config.addReviewButton).hide();

        $.ajax({
            url: config.url,
            method: 'post',
            data: {
                productId: $('input[name="product"]').val(),
                isAjax: true
            },
            dataType: 'html',
            success: function(res) {
                if (res.length) {
                    // @TODO: seems that the placement is not correct
                    $(element).html(res).appendTo(config.appendTo);
                    $(config.addReviewButton).hide();
                    $(config.reviewFormFieldset).hide();
                    $(config.reviewFormActions).hide();
                    $(element).trigger('contentUpdated');
                } else {
                    $(element).html('');
                    $(config.addReviewButton).show();
                    $(config.reviewFormFieldset).show();
                    $(config.reviewFormActions).show();
                }
            }
        });
    }
});
