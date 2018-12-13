define([
    'jquery',
    'mage/url',
    'mage/translate'
], function ($, urlBuilder, $tr) {
    'use strict';

    return function () {
        var uploadUrl = urlBuilder.build('yosto_order_attachment/attachment/upload');
        var form = $('#yosto-order-attachment')[0];
        $('.order-attachment-loading').show();
        $('#order-attachment-upload-btn').attr('disabled', 'disabled');
        $.ajax({
            url: uploadUrl, // Url to which the request is send
            type: "POST",             // Type of request to be send, called as method
            data: new FormData(form), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,             // To unable request pages to be cached
            processData:false,        // To send DOMDocument or non processed data file it is set to false
            success: function(data)   // A function to be called if request succeeds
            {
                $('.order-attachment-loading').hide();
                if (data.success == 1) {
                    $('.order-attachment-upload-result')
                        .removeClass("fail")
                        .addClass("success")
                        .text($tr("Success"))
                        .fadeIn(500)
                        .fadeOut(1000);
                    window.checkoutConfig.yosto_order_attachment.file_upload_status = 1;
                } else {
                    $('.order-attachment-upload-result')
                        .removeClass('success')
                        .addClass('fail')
                        .text($tr("Upload Failed"))
                        .fadeIn(500)
                        .fadeOut(1000);
                    window.checkoutConfig.yosto_order_attachment.file_upload_status = 0;
                }
                $('#order-attachment-upload-btn').removeAttr('disabled');

            },
            fail: function(data) {
                $('.order-attachment-loading').hide();
                $('.order-attachment-upload-result')
                    .removeClass('success')
                    .addClass('fail')
                    .text($tr("Upload Failed"))
                    .fadeIn(500)
                    .fadeOut(1000);
                $('#order-attachment-upload-btn').removeAttr('disabled');
                window.checkoutConfig.yosto_order_attachment.file_upload_status = 0;
            }
        });

    }
});