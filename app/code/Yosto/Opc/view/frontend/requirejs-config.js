/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/step-navigator': {
                'Yosto_Opc/js/model/step-navigator-mixin': true
            },
            'Magento_Checkout/js/view/summary/item/details': {
                'Yosto_Opc/js/view/summary/item/details': true
            }
        }
    },
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/default': 'Yosto_Opc/js/model/shipping-save-processor/default'
        }
    }
};
