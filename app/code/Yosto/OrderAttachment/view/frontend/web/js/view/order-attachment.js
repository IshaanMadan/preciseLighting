define([
    'ko',
    'jquery',
    'uiComponent',
    'Yosto_OrderAttachment/js/action/file-uploader',
    "mage/translate"
], function (ko, $, Component, fileUploader, $tr) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Yosto_OrderAttachment/order-attachment'
        },
        uploadFile: function () {
            var validateResult = this.validateSize();
            if (validateResult) {
                fileUploader();
            }
        },
        formKey: function() {
            return jQuery.cookie('form_key');
        },
        isEnable: function () {
          return  window.checkoutConfig.yosto_order_attachment.enable;
        },
        getTitle: function () {
            return window.checkoutConfig.yosto_order_attachment.title;
        },
        isRequired: function () {
            return window.checkoutConfig.yosto_order_attachment.is_required;
        },
        getFileExtensions: function () {
            return window.checkoutConfig.yosto_order_attachment.file_extensions;
        },
        getLimitedFileSize: function () {
            return window.checkoutConfig.yosto_order_attachment.file_size;
        },
        getAllowCustomerEdit: function () {
            return window.checkoutConfig.yosto_order_attachment.allow_customer_edit;
        },
        getClassForField: function () {
            if (this.isRequired() == 1) {
                return "field _required";
            } else {
                return "field";
            }
        },
        getClassForInput: function () {
            if (this.isRequired() == 1) {
                return "true";
            } else  {
                return "";
            }
        },
        validateSize: function () {
            var file = document.getElementById("yosto_order_attachment").files[0]
            var fileSize = file.size/1024/1024;
            var limitedFileSize = this.getLimitedFileSize();
            if (fileSize > limitedFileSize) {
                $(".order-attachment-validate-message").html($tr("File size must be less than limited size"));
                $('#order-attachment-upload-btn').attr('disabled', 'disabled');
                return false;
            } else {
                $(".order-attachment-validate-message").html('');
                $('#order-attachment-upload-btn').removeAttr('disabled');
            }
            return true;
        }

    });
});