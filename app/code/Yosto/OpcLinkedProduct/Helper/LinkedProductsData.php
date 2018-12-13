<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OpcLinkedProduct\Helper;

/**
 * Class LinkedProductsData
 * @package Yosto\OpcLinkedProduct\Helper
 */
class LinkedProductsData
{
    protected $_currentCart;

    protected $_linkCollectionFactory;

    protected $_productCollectionFactory;

    function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ResourceModel\Product\Link\CollectionFactory $linkCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->_currentCart = $cart;
        $this->_linkCollectionFactory = $linkCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Item[]
     */
    public function getCurrentItems()
    {
        return $this->_currentCart->getQuote()->getAllVisibleItems();
    }

    /**
     * @param $linkType
     * @param $limitedNumber
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProducts($linkType, $limitedNumber)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $itemCollection */
        $itemCollection = $this->_productCollectionFactory->create();

        $itemCollection->addFieldToFilter('entity_id', ["in" => $this->getLinkedProductIds($linkType, $limitedNumber)])
            ->addAttributeToSelect(
                'required_options'
            )->addStoreFilter();

        return $itemCollection;
    }

    /**
     * @param $linkType
     * @param $limitedNumber
     * @return array|mixed
     */
    public function getLinkedProductIds($linkType, $limitedNumber)
    {
        $items  = $this->getCurrentItems();
        $productIds = $this->getRealProductIds($items);
        $links = $this->_linkCollectionFactory->create()
            ->addFieldToFilter('product_id', ['in' => $productIds])
            ->addFieldToFilter('link_type_id', $linkType );
        $linkedProductIds = $links->getColumnValues('linked_product_id');
        if (count($linkedProductIds) > 0) {
            $linkedProductIds = array_unique($linkedProductIds);
            if (count($linkedProductIds) > $limitedNumber) {
                return array_rand(array_flip($linkedProductIds), $limitedNumber);
            } else {
                return $linkedProductIds;
            }
        }

        return [];
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item[] $items
     * @return array
     */
    public function getRealProductIds($items)
    {
        $realProductIds = [];
        foreach ($items as $item) {
            if ($item->getParentItemId()) {
                $realProductIds[] = $item->getParentItem()->getData('product_id');
            } else {
                $realProductIds[] = $item->getData('product_id');
            }
        }
        return $realProductIds;
    }
}