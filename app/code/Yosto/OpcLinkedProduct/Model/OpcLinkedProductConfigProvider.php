<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OpcLinkedProduct\Model;


use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\View\LayoutInterface;

/**
 * Class OpcLinkedProductConfigProvider
 * @package Yosto\OpcLinkedProduct\Model
 */
class OpcLinkedProductConfigProvider implements ConfigProviderInterface
{
    protected $_layout;

    function __construct(LayoutInterface $layout)
    {
        $this->_layout = $layout;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'linked_products' => $this->_layout
                ->createBlock(\Yosto\OpcLinkedProduct\Block\LinkedProducts::class)
                ->toHtml()
        ];
    }

}