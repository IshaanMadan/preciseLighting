<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review;

class Form extends \Magento\Framework\View\Element\Template
{
    /** @var \MageWorkshop\DetailedReview\Helper\Attribute $helper */
    protected $helper;

    /** @var \Magento\Framework\Registry $coreRegistry */
    protected $coreRegistry;

    /**
     * Form constructor.
     * @param \MageWorkshop\DetailedReview\Helper\Attribute $helper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageWorkshop\DetailedReview\Helper\Attribute $helper,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @return string
     */
    public function getReviewFieldsConfigurationJson()
    {
        $configuration = $this->getHelper()->getReviewFormAttributesConfiguration($this->getProduct());
        return json_encode($configuration);
    }

    /**
     * @return \MageWorkshop\DetailedReview\Helper\Attribute
     */
    protected function getHelper()
    {
        return $this->helper;
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
//        if (!$this->coreRegistry->registry('product') && $this->getProductId()) {
//            $product = $this->productRepository->getById($this->getProductId());
//            $this->coreRegistry->register('product', $product);
//        }
        return $this->coreRegistry->registry('product');
    }
}
