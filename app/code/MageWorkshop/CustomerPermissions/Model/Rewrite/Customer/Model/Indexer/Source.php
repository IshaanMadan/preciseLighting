<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * DESCRIPTION!
 * Class `Magento\Customer\Model\Indexer\Source` does not exist in Magento 2.1.x
 * But compiler scans for all classes and fails to compile the code
 * This is why we do this :(
 */
namespace MageWorkshop\CustomerPermissions\Model\Rewrite\Customer\Model\Indexer;

if (!class_exists('\Magento\Customer\Model\Indexer\Source')) {
    class Source {}
} else {
    require_once 'Source.phtml';
}
