<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Attribute\Edit\Tab\Options;

class Visual extends \Magento\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual
{
    /** @var \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface */
    protected $productMetadataInterface;

    const MAGENTO_200_TEMPLATE = 'MageWorkshop_DetailedReview::review/attribute/visual_m200.phtml';

    /**
     * Visual constructor.
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Framework\Validator\UniversalFactory $universalFactory
     * @param \Magento\Catalog\Model\Product\Media\Config $mediaConfig
     * @param \Magento\Swatches\Helper\Media $swatchHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Catalog\Model\Product\Media\Config $mediaConfig,
        \Magento\Swatches\Helper\Media $swatchHelper,
        \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface,
        array $data
    ) {
        parent::__construct(
            $context,
            $registry,
            $attrOptionCollectionFactory,
            $universalFactory,
            $mediaConfig,
            $swatchHelper,
            $data
        );
        $this->productMetadataInterface = $productMetadataInterface;
    }

    protected function _prepareLayout()
    {
        $this->productMetadataInterface->getVersion();
        if ($template = $this->getVersionDependentTemplate()) {
            $this->setTemplate($template);
        }

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getVersionDependentTemplate()
    {
        $template = '';
        if (version_compare($this->productMetadataInterface->getVersion(), '2.1.0') < 0) {
            $template = self::MAGENTO_200_TEMPLATE;
        }
        return $template;
    }

    /**
     * Get JSON config
     * This method overrides the default one because in Magento 2.1 the iframe target URL is now set here
     * and we should rewrite it to our own controller
     *
     * @return string
     */
    public function getJsonConfig()
    {
        // parent::getJsonConfig() available since Magento v2.1.0 and is called only since that version
        // @IGNORE parent::getJsonConfig()
        $config = json_decode(parent::getJsonConfig(), true);
        if (isset($config['uploadActionUrl'])) {
            $config['uploadActionUrl'] = $this->getUrl('mageworkshop_detailedreview/iframe/show');
        }
        return json_encode($config);
    }
}
