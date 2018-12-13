<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Controller\Index;

/**
 * Class SaveAdditionalData
 * @package Yosto\Opc\Controller\Index
 */
class SaveAdditionalData extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        /** @var \Magento\Framework\DataObject $dataObject */
        $additionalData = $this->_objectManager->create(
            \Magento\Framework\DataObject::class,
            ["data" => json_decode($this->getRequest()->getContent(), true)]

        );

        /** @var \Magento\Checkout\Model\Session $checkoutSession */
        $checkoutSession = $this->_objectManager->get(\Magento\Checkout\Model\Session::class);
        $checkoutSession->setData('yosto_opc_order_comment', $additionalData->getData('yosto_opc_order_comment'));
        $checkoutSession->setData('yosto_opc_subscribe_newsletter', $additionalData->getData('yosto_opc_subscribe_newsletter'));
    }
}