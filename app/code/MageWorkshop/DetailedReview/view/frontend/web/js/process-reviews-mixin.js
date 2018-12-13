/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    // Need to initialize tabs and all containers first!
    'mage/utils/wrapper',
    'tabs'
], function (wrapper) {
    // Original method sends the HTML request to the ListAjax controller. Cookies are sent together with this
    // request BEFORE the mage-messages cookie is cleared. The script "Magento_Theme/js/view/messages" is
    // the app component that is initialized AFTER the reviews block. This is why we must at least wait a little :(
    // Otherwise messages are always shown on the Product page. This refers to all messages, not only to the reviews
    return function (processReviews) {
        return wrapper.wrap(processReviews, function (originalProcessReviews, config) {
            setTimeout(originalProcessReviews, 1000);
        });
    };
});
