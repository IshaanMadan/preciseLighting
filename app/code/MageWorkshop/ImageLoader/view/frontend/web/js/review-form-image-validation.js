/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    if (!$.hasOwnProperty('mageWorkshop')) {
        $.mageWorkshop = {};
    }

    $.widget('mageWorkshop.mageWorkshop_imageLoader_reviewFormImageValidation', {
        options: {
            maxSize: 1,
            extensions: []
        },

        _create: function () {
            this.validateFileExtensions();
            this.validateFilesize();
        },

        //Validate Image Extensions
        validateFileExtensions: function () {
            var that = this;
            $.validator.addMethod(
                'validate-file-extensions', function (v, elm) {

                    var value = elm.value;
                    if (!v) {
                        return true;
                    }
                    if (value !== undefined) {
                        var ext = value.substring(value.lastIndexOf('.') + 1);
                        for (var i = 0; i < that.options.extensions.length; i++) {
                            if (ext === that.options.extensions[i]) {
                                return true;
                            }
                        }
                    }

                    return false;
                }, $.mage.__('Disallowed file type.'));
        },

        //Validate Image FileSize
        validateFilesize: function () {
            var that = this;
            $.validator.addMethod(
                'validate-file-size', function (v, elm) {
                    var maxSize = that.options.maxSize * 1024 * 1024;
                    if (navigator.appName === "Microsoft Internet Explorer") {
                        if (elm.value) {
                            var oas = new ActiveXObject("Scripting.FileSystemObject");
                            var e = oas.getFile(elm.value);
                            var size = e.size;
                        }
                    } else {
                        if (elm.files[0] !== undefined) {
                            size = elm.files[0].size;
                        }
                    }

                    return !(size !== undefined && size > maxSize);
            }, $.mage.__('The file size should not exceed ' + this.options.maxSize + 'MB'));
        }
});

    return $.mageWorkshop.mageWorkshop_imageLoader_reviewFormImageValidation;
});
