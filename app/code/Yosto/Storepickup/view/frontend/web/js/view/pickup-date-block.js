define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Yosto_Storepickup/pickup-date-block'
        },
        initialize: function () {
            this._super();
            var disabled = window.checkoutConfig.shipping.pickup_date.disabled;
            var noday = window.checkoutConfig.shipping.pickup_date.noday;
            var hourMin = parseInt(window.checkoutConfig.shipping.pickup_date.hourMin);
            var hourMax = parseInt(window.checkoutConfig.shipping.pickup_date.hourMax);
            var format = window.checkoutConfig.shipping.pickup_date.format;
            this.visible = (window.checkoutConfig.yosto_storepickup.active == 1);
            if (!format) {
                format = 'yy-mm-dd';
            }
            var disabledDay = disabled.split(",").map(function (item) {
                return parseInt(item, 10);
            });

            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    var $el = $(element);
                    //initialize datetimepicker
                    if (noday) {
                        var options = {
                            minDate: 0,
                            dateFormat:format,
                            hourMin: hourMin,
                            hourMax: hourMax
                        };
                    } else {
                        var options = {
                            minDate: 0,
                            dateFormat:format,
                            hourMin: hourMin,
                            hourMax: hourMax,
                            beforeShowDay: function (date) {
                                var day = date.getDay();
                                if (disabledDay.indexOf(day) > -1) {
                                    return [false];
                                } else {
                                    return [true];
                                }
                            }
                        };
                    }

                    $el.datetimepicker(options);

                    var writable = valueAccessor();
                    if (!ko.isObservable(writable)) {
                        var propWriters = allBindingsAccessor()._ko_property_writers;
                        if (propWriters && propWriters.datetimepicker) {
                            writable = propWriters.datetimepicker;
                        } else {
                            return;
                        }
                    }
                    writable($(element).datetimepicker("getDate"));
                },
                update: function (element, valueAccessor) {
                    var widget = $(element).data("DateTimePicker");
                    //when the view model is updated, update the widget
                    if (widget) {
                        var date = ko.utils.unwrapObservable(valueAccessor());
                        widget.date(date);
                    }
                }
            };

            return this;
        },
        isVisible: function () {
            return (window.checkoutConfig.yosto_storepickup.active == 1);
        }
    });
});
