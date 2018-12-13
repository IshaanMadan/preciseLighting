/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'jquery/ui',
    'mage/validation'
], function($) {
    'use strict';

    $.widget('mage.verifyBuyer', {

        options: {
            processStart: 'processStart',
            processStop: 'processStop',
            emailElementId: '#verify-email',
            reviewFormId: '#review-form',
            verifyButton: '#verify-button',
            reviewFormFieldset: '.review-fieldset',
            reviewFormActions: '.review-form-actions',
            addReviewButton: '.review-average-info a.add,.product-reviews-summary a.add',
        },

        _create: function() {
            this._bindClick();
            $(this.options.addReviewButton).show();
        },

        _bindClick: function() {
            var self = this;
            $('body').on('click', self.options.verifyButton, function(e) {
                if($(self.element).validation() && $(self.element).validation('isValid')
                ) {
                    e.preventDefault();
                    self.ajaxSubmit($(self.options.emailElementId).val());
                }
            });
        },

        isLoaderEnabled: function() {
            return this.options.processStart && this.options.processStop;
        },

        ajaxSubmit: function(email) {
            var self = this;
            $.ajax({
                url: self.options.submitUrl,
                data: {
                    "verify-email": email,
                    productId: $('input[name="product"]').val(),
                    isAjax: true
                },
                type: 'post',
                dataType: 'html',
                beforeSend: function() {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStart);
                    }
                },
                success: function(res) {
                    if (self.isLoaderEnabled()) {
                        $('body').trigger(self.options.processStop);
                    }
                    if (res.length) {
                        $(self.options.resultBlock).html(res);
                        $(self.options.addReviewButton).hide();
                        $(self.options.resultBlock).trigger('contentUpdated');
                    } else {
                        $(self.options.resultBlock).html('');
                        $(self.options.reviewFormFieldset).show();
                        $(self.options.reviewFormActions).show();
                    }
                }
            });
        }
    });

    return $.mage.verifyBuyer;
});