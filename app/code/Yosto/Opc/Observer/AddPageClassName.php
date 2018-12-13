<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Observer;

class AddPageClassName implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $helper;

    /**
     */
    public function __construct(
        \Magento\Framework\View\Page\Config $pageConfig,
        \Yosto\Opc\Helper\Data $helper
    ) {
        $this->pageConfig = $pageConfig;
        $this->helper = $helper;
    }

    /**
     * Add FontAwesome assets according to module config
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		if (!$this->helper->isOnCheckoutPage()) {
			return;
		}	
        $this->pageConfig->addBodyClass('yosto-opc')
            ->addBodyClass('checkout-index-index');

        foreach ($this->helper->getLayoutClassNames() as $class) {
            $this->pageConfig->addBodyClass($class);
        }
    }
}
