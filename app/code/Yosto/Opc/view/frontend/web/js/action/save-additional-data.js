/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'mage/storage'
    ],
    function (ko, $, storage) {
        'use strict';
        return function () {
            var deferred = $.Deferred();
            var orderComment, subscribeNewsletter = '';
            var orderCommentElm = $('#yosto-opc-order-comment');
            var subscribeNewsletterElm = $('#newsletter-subscriber-checkbox');

            if (orderCommentElm.length > 0) {
                orderComment = orderCommentElm.val();
            } else {
                orderComment = '';
            }

            if (subscribeNewsletterElm.length > 0) {
                if (subscribeNewsletterElm.attr('checked') == 'checked') {
                    subscribeNewsletter = 1;
                } else {
                    subscribeNewsletter = 0;
                }
            }
            var params = {
                'yosto_opc_order_comment': orderComment,
                'yosto_opc_subscribe_newsletter': subscribeNewsletter
            };

            if (orderComment || subscribeNewsletter) {
                storage.post(
                    'yosto_opc/index/saveAdditionalData',
                    JSON.stringify(params),
                    false
                ).done(
                    function (result) {

                    }
                ).fail(
                    function (result) {

                    }
                ).always(
                    function (result) {
                        deferred.resolve(result);
                    }
                );
            } else {
                deferred.resolve('');
            }


            return deferred;
        };
    }
);
