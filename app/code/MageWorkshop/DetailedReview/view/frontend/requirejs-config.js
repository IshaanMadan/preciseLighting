/*
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    paths: {
        mageWorkshop_detailedReview_reviewFilters: 'MageWorkshop_DetailedReview/js/review-filters',
        mageWorkshop_detailedReview_reviewRating: 'MageWorkshop_DetailedReview/js/review-rating',
        mageWorkshop_detailedReview_listReviews: 'MageWorkshop_DetailedReview/js/list-reviews',
        mageWorkshop_detailedReview_reviewForm: 'MageWorkshop_DetailedReview/js/review-form',
        mageWorkshop_detailedReview_reviewCustomValidateLength: 'MageWorkshop_DetailedReview/js/review-custom-validate-length'
    },
    config: {
        mixins: {
            'Magento_Review/js/process-reviews': {
                'MageWorkshop_DetailedReview/js/process-reviews-mixin': true
            }
        }
    }
};
