<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\OpcLinkedProduct\Block;


use Magento\Catalog\Block\Product\ProductList\Related;
use Magento\Catalog\Model\Product\Link;
use Magento\CatalogWidget\Block\Product\ProductsList;
use Magento\Framework\App\ObjectManager;
use Yosto\OpcLinkedProduct\Helper\ConfigData;
use Yosto\OpcLinkedProduct\Helper\LinkedProductsData;

/**
 * Class LinkedProducts
 * @package Yosto\OpcLinkedProduct\Block
 */
class LinkedProducts extends Related
{
    protected $_template = "Yosto_OpcLinkedProduct::product/list/items.phtml";

    protected $_linkedProductsData;

    protected $_helper;


    public function __construct(
        LinkedProductsData $linkedProductsData,
        ConfigData $configData,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_linkedProductsData = $linkedProductsData;
        $this->_helper = $configData;
        parent::__construct($context, $checkoutCart, $catalogProductVisibility, $checkoutSession, $moduleManager, $data);
    }

    /**
     * @return $this
     */
    protected function _prepareData()
    {

        $limitedNumber = $this->_helper->getNumberOfItems();
        $linkType = $this->_helper->getLinkType();

        $this->_itemCollection = $this->_linkedProductsData->getProducts($linkType, $limitedNumber);

        if ($this->moduleManager->isEnabled('Magento_Checkout')) {
            $this->_addProductAttributesAndPrices($this->_itemCollection);
        }
        $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $this->_itemCollection->load();

        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        $type = $this->_helper->getLinkType();
        return $type;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_helper->getTitle();
    }
}