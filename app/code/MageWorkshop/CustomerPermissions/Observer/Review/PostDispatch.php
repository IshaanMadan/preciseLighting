<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Observer\Review;

use Magento\Framework\Event\Observer;
use MageWorkshop\CustomerPermissions\Observer\Review\SaveAfter as SaveAfterObserver;

class PostDispatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\Message\ManagerInterface $messageManager
     */
    private $messageManager;

    /**
     * PreDispatch constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->registry = $registry;
        $this->messageManager  = $messageManager;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->registry->registry(SaveAfterObserver::REGISTRY_KEY_REVIEW_APPROVED_AUTOMATICALLY)
            && $this->messageManager->hasMessages()
        ) {
            foreach ($this->messageManager->getMessages()->getItems() as $message) {
                if ($message instanceof \Magento\Framework\Message\Success
                    && $message->getText() === (string) __('You submitted your review for moderation.')
                ) {
                    $message->setText('Your review was automatically approved');
                }
            }
        }
    }
}
