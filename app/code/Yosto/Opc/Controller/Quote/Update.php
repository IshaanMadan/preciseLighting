<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Controller\Quote;

use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class Update
 * @package Yosto\Opc\Controller\Quote
 */
class Update extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Sidebar
     */
    protected $_sidebar;

    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @var JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Update constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Sidebar $sidebar
     * @param \Yosto\Opc\Helper\Data $helper
     * @param \Magento\Checkout\Model\Cart $cart
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Sidebar $sidebar,
        \Yosto\Opc\Helper\Data $helper,
        \Magento\Checkout\Model\Cart $cart,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->_helper = $helper;
        $this->cart = $cart;
        $this->_sidebar = $sidebar;
        $this->_jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\DataObject $qtyData */
        $qtyData = $this->_objectManager->create(
            \Magento\Framework\DataObject::class,
            ["data" => json_decode($this->getRequest()->getContent(), true)]
        );

        $updateType = $qtyData->getData('updateType');
        $result = array();
        $result['error'] = '';
        $item = $this->cart->getQuote()->getItemById($qtyData->getData('itemId'));
        $oldQty = $item->getQty();
        try {
            if ($updateType == 'update') {
                $this->_sidebar->checkQuoteItem($qtyData->getData('itemId'));
                $this->_sidebar->updateQuoteItem($qtyData->getData('itemId'), $qtyData->getData('qty'));
            } else {
                $this->_sidebar->removeQuoteItem($qtyData->getData('itemId'));
            }

        } catch (\Exception $e) {
            $this->_sidebar->updateQuoteItem($qtyData->getData('itemId'), $oldQty);
            $result['error'] = $e->getMessage();
        }

        if ($this->_helper->enableGiftWrap()) {
            $giftWrapAmount = $this->_helper->getOrderGiftWrapAmount();
            $result['giftwrap_amount'] = $giftWrapAmount;
        } 
        if($this->cart->getSummaryQty() == 0){
            $result['cartEmpty'] = true;
        }

        if ($this->cart->getQuote()->isVirtual()) {
            $result['is_virtual'] = true;
        } else {
            $result['is_virtual'] = false;
        }

        $resultJson = $this->_jsonFactory->create();
        return $resultJson->setData($result);
    }
}