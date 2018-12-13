/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
define([
    'jquery',
    'mage/sticky'
], function($) {
    $.widget('yostoopc.stickySummary', $.mage.sticky, {
        options: {
            container: '.yosto-opc #checkout',
            offsetTop: 25
        },
        _create: function() {
            this._super();

            // body height is not a constant
            setInterval(this._calculateDimens.bind(this), 500);
        },

        _stick: function() {
            var offset,
                isStatic;

            isStatic = this.element.css('position') === 'static';

            if( !isStatic && this.element.is(':visible') ) {
                offset = $(document).scrollTop() - this.parentOffset + this.options.offsetTop;
                offset = Math.max( 0, Math.min( offset, this.maxOffset) );
                this.element.css( 'top', offset );
            }
        }
    });

    return $.yostoopc.stickySummary;
});