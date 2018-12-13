define([
    'jquery',
    'mage/url',
    'mage/translate'
], function ($, urlBuilder, $tr) {
    'use strict';

    return function (requestUrl, mediaUrl) {
        var uploadUrl = requestUrl;
        var form = $('#yosto-order-attachment')[0];
        $('.order-attachment-loading').show();

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
                    var path = data.path;
                    var fileName = path.substring(path.lastIndexOf('/')+1);
                    $('#yosto-order-attachment-file').attr('href', mediaUrl + path).text(fileName);
                    $('.order-attachment-upload-result')
                        .removeClass("fail")
                        .addClass("success")
                        .text($tr("Success"))
                        .fadeIn(500)
                        .fadeOut(1000);
                } else {
                    $('.order-attachment-upload-result')
                        .removeClass('success')
                        .addClass('fail')
                        .text($tr("Upload Failed"))
                        .fadeIn(500)
                        .fadeOut(1000);
                }
            },
            fail: function(data) {
                $('.order-attachment-loading').hide();
                $('.order-attachment-upload-result')
                    .removeClass('success')
                    .addClass('fail')
                    .text($tr("Upload Failed"))
                    .fadeIn(500)
                    .fadeOut(1000);
            }
        });

    }
});