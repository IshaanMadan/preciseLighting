<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\ImageLoader\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper
     */
    private $attributeHelper;

    /**
     * UpgradeData constructor.
     * @param \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper
     */
    public function __construct(
        \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper
    ) {
        $this->attributeHelper = $attributeHelper;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.1.1', '<')) {
            foreach ($this->attributeHelper->getAttributesCollection() as $attribute) {
                if ($attribute->getFrontendInput() === 'media_image') {
                    $attribute->setFrontendInput('image');
                    $attribute->save();
                }
            }
        }
    }
}
