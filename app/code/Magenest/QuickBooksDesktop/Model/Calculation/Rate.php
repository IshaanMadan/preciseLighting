<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\Calculation;

/**
 * Class Rate
 * @package Magenest\QuickBooksDesktop\Model\Calculation
 */
class Rate extends \Magento\Tax\Model\Calculation\Rate
{
    protected $_eventPrefix = 'tax_rate';
}
