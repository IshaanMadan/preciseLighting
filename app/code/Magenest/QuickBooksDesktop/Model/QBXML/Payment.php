<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magenest\QuickBooksDesktop\Model\Mapping;
use Magenest\QuickBooksDesktop\Model\QBXML;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class Payment extends QBXML
{

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * Item constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Mapping $map
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_map = $map;
    }

    /**
     * Get XML using sync to QBD
     * @param $code
     * @return string
     */
    public function getXml($code)
    {
        $paymentMethodsList = $this->_scopeConfig->getValue('payment');
        foreach ($paymentMethodsList as $check => $data) {
            if ($check == $code) {
                if (isset($data['title'])) {
                    $title = $data['title'];
                    if (strlen($title) > 31) {
                        $title = substr($title, 0, 31);
                    }
                }
            }
        }
        $xml = '<Name>'.$title.'</Name>';
        return $xml;
    }
}
