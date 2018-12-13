/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'jquery/ui'
], function($, alert) {
    'use strict';

    if (!$.hasOwnProperty('mageWorkshop')) {
        $.mageWorkshop = {};
    }

    $.widget('mageWorkshop.voting_voting', {

        options: {
            processStart: 'processStart',
            processStop: 'processStop',
            buttons: {
                vote: '.review-vote'
            },
            labels: {
                helpfulQty: '.helpful-qty',
                unhelpfulQty: '.unhelpful-qty'
            },
            dataAttributes: {
                buttonVoteValue: 'vote-value',
                currentCustomerVote: 'current-customer-vote'
            },
            alert: {
                title: '',
                timeout: 3000
            }
        },

        _create: function() {
            this.$voteButton = this.element.find(this.options.buttons.vote);
            this.$helpfulQuantityLabel = this.element.find(this.options.labels.helpfulQty);
            this.$unhelpfulQuantityLabel = this.element.find(this.options.labels.unhelpfulQty);
            this._bind();
        },

        _bind: function() {
            this._on(this.$voteButton, {click: this._voteButtonClick});
        },

        _voteButtonClick: function(event) {
            event.preventDefault();
            var currentCustomerVote = this.element.data(this.options.dataAttributes.currentCustomerVote) + 0;
            var buttonVoteValue = $(event.currentTarget).data(this.options.dataAttributes.buttonVoteValue) + 0;
            if (currentCustomerVote === buttonVoteValue) { // toggle my vote if I pressed the same button
                buttonVoteValue = 0;
            }
            this._off(this.$voteButton,'click');
            this._ajaxSubmit(buttonVoteValue);
        },

        _isLoaderEnabled: function() {
            return this.options.processStart && this.options.processStop;
        },

        _ajaxSubmit: function(voteValue) {
            $.ajax({
                context: this,
                url: this.options.submitUrl,
                data: {
                    "vote_value": voteValue
                },
                type: 'post',
                dataType: 'json',
                beforeSend: this._ajaxBeforeSend,
                success: this._ajaxSuccess,
                error: this._ajaxError
            });
        },

        _ajaxBeforeSend: function() {
            if (this._isLoaderEnabled()) {
                $('body').trigger(this.options.processStart);
            }
        },

        _ajaxSuccess: function(responseData) {
            this.element.data(this.options.dataAttributes.currentCustomerVote, responseData.currentCustomerVote);
            this.$helpfulQuantityLabel.html(responseData.helpfulCount);
            this.$unhelpfulQuantityLabel.html(responseData.unhelpfulCount);

            this.alert(responseData.message);
            this._ajaxAfterReceive()
        },

        _ajaxError: function(xhr, status, error) {
            this.alert(xhr.responseJSON.error);
            this._ajaxAfterReceive()
        },

        _ajaxAfterReceive: function() {
            if (this._isLoaderEnabled()) {
                $('body').trigger(this.options.processStop);
            }
            this._bind();
        },

        alert: function(message) {
            var $alert = alert({
                title: this.options.alert.title,
                modalClass: 'confirm voting-notification',
                content: message
            });

            setTimeout(function() {
                $alert.alert('closeModal');
            }, this.options.alert.timeout);
        }
    });

    return $.mageWorkshop.voting_voting;
});