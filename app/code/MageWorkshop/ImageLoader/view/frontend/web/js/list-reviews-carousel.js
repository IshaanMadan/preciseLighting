/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mageWorkshop_imageLoader_owlCarousel',
    'mageWorkshop_imageLoader_fancyBox'
], function ($) {
    'use strict';

    if (!$.hasOwnProperty('mageWorkshop')) {
        $.mageWorkshop = {};
    }

    $.widget('mageWorkshop.mageWorkshop_imageLoader_listReviewsCarousel', {

        _create: function () {
            this.carousel();
            this.setCarouselAttributes();
        },

        carousel: function () {
            $(this.element).owlCarousel({
                nav: true,
                lazyLoad: true,
                responsiveClass: true,
                responsive:{
                    0:{
                        items: 1,
                        nav: true
                    },
                    640:{
                        items: 3,
                        nav: true
                    },
                    1024:{
                        items: 4,
                        nav: true,
                        touchDrag: false,
                        freeDrag: false
                    }
                }
            });
        },

        setCarouselAttributes: function () {
            $('.owl-carousel img').width(
                this.options.imageWidth
            ).height(
                this.options.imageHeight
            );
        }
    });

    return $.mageWorkshop.mageWorkshop_imageLoader_listReviewsCarousel;
});