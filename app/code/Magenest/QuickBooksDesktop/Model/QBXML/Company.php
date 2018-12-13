<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magenest\QuickBooksDesktop\Model\QBXML;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;

/**
 * Class Company
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class Company extends QBXML
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Item constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get XML using sync to QBD
     *
     * @return string
     */
    public function getXml($id)
    {
        return '';
    }
}
