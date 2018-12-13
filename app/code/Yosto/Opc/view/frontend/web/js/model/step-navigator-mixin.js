define([
    'mage/utils/wrapper',
    'ko'
], function(wrapper,ko) {
    'use strict';

    var checkoutConfig = window.checkoutConfig;
    return function(target) {
        target.registerStep = wrapper.wrap(
            target.registerStep,
            function (originalAction, code, alias, title, isVisible, navigate, sortOrder) {
                arguments[4] = wrapper.wrap(
                    isVisible,
                    function(o, flag) {
                        // make section always visible
                        return o(true);
                    }
                );

                return originalAction.apply(
                    target,
                    Array.prototype.slice.call(arguments, 1)
                );
            }
        );

        target.getActiveItemIndex =
            function () {
                var activeIndex = 0;

                target.steps.sort(this.sortItems).forEach(function (element, index) {
                    if (element.isVisible()) {
                        activeIndex = index;

                        return true;
                    }

                    return false;
                });

                return activeIndex;
            };
        return target;
    }
});
