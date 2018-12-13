<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Controller\Index;

/**
 * Class GiftwrapProcess
 * @package Yosto\Opc\Controller\Index
 */
class GiftwrapProcess extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Quote\Model\Quote\TotalsCollector
     */
    protected $_totalsCollector;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $_quoteRepository;

    /**
     * GiftwrapProcess constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector
    ) {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_quoteRepository = $quoteRepository;
        $this->_totalsCollector = $totalsCollector;

    }

    /**
     * @return $this
     */
    public function execute()
    {
        /** @var \Magento\Framework\DataObject $qtyData */
        $data = $this->_objectManager->create(
            \Magento\Framework\DataObject::class,
            ["data" => json_decode($this->getRequest()->getContent(), true)]

        );
        
        $isChecked = $data->getData('isChecked');

        if ($isChecked) {
            $this->_checkoutSession->setData('yosto_opc_giftwrap', 1);
        } else {
            $this->_checkoutSession->unsetData('yosto_opc_giftwrap');
            $this->_checkoutSession->unsetData('yosto_opc_giftwrap_amount');
        }
        
        $quote = $this->_checkoutSession->getQuote();
        $this->_totalsCollector->collectQuoteTotals($quote);
        $this->_quoteRepository->save($quote);
        $this->getResponse()->setBody($this->_checkoutSession->getData('yosto_opc_giftwrap_amount'));
    }
}
