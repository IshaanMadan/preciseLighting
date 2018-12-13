define([
    'ko',
    'jquery',
    'Yosto_OrderAttachment/js/action/file-uploader',
    'mage/translate'
], function (ko, $, fileUploader, $tr) {
    'use strict';
    return function (config) {
        $('#yosto-order-attachment').on('submit', function (e) {
            e.preventDefault();
            var baseUrl = config.mediaUrl;
            var requestUrl = config.requestUrl;
            fileUploader(requestUrl,baseUrl);
        });
        $('#yosto_order_attachment').on('change', function (e) {
            var file = document.getElementById("yosto_order_attachment").files[0]
            var fileSize = file.size/1024/1024;
            var limitedFileSize = $(this).data('size');
            if (fileSize > limitedFileSize) {
                $(".order-attachment-validate-message").html($tr("File size must be less than limited size"));
                $('#order-attachment-upload-btn').attr('disabled', 'disabled');
                return false;
            } else {
                $(".order-attachment-validate-message").html('');
                $('#order-attachment-upload-btn').removeAttr('disabled');
            }
            return true;
        });

    }
});