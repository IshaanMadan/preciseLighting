<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review\Product\View;

/**
 * Class ListView
 *
 * @method string getDateFormat()
 */
class ListView extends \Magento\Review\Block\Product\View\ListView
{
    const EVENT_NAME_PREPARE_REVIEW_COLLECTION = 'prepare_review_collection_for_review_list';
    const QUANTITY_WORD_FOR_CUT = 400;

    /** @var \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper */
    protected $attributeHelper;

    /** @var \MageWorkshop\Core\Helper\View */
    protected $viewHelper;

    /**
     * ListView constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory
     * @param \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper
     * @param \MageWorkshop\Core\Helper\View $viewHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \MageWorkshop\DetailedReview\Helper\Attribute $attributeHelper,
        \MageWorkshop\Core\Helper\View $viewHelper,
        array $data
    ) {
        $this->attributeHelper = $attributeHelper;
        $this->viewHelper = $viewHelper;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $collectionFactory,
            $data
        );
    }

    /**
     * @return \Magento\Review\Model\ResourceModel\Review\Collection
     */
    public function getReviewsCollection()
    {
        if ($this->_reviewsCollection === null) {
            $reviewsCollection = parent::getReviewsCollection();
            $this->_eventManager->dispatch(
                self::EVENT_NAME_PREPARE_REVIEW_COLLECTION,
                [
                    'collection' => $reviewsCollection,
                    'product'    => $this->getProduct()
                ]
            );
        }
        return $this->_reviewsCollection;
    }

    /**
     * Note: method is used in the template and in the $this::getOptions() method
     *
     * @return \MageWorkshop\DetailedReview\Model\ResourceModel\Attribute\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getReviewFormAttributes()
    {
        return $this->attributeHelper->getReviewFormAttributes($this->getProduct());
    }

    public function getLabelClass(\MageWorkshop\DetailedReview\Model\Attribute $attribute)
    {
        return $this->attributeHelper->isVisualSwatch($attribute)
            ? 'swatch'
            : $attribute->getFrontendInput();
    }

    /**
     * @param \MageWorkshop\DetailedReview\Model\Attribute $attribute
     * @param \Magento\Review\Model\Review $review
     * @return array|string
     */
    public function getAttributeValue(
        \MageWorkshop\DetailedReview\Model\Attribute $attribute,
        \Magento\Review\Model\Review $review
    ) {
        $value = (string) $review->getData($attribute->getAttributeCode());
        switch ($attribute->getFrontendInput()) {
            case 'textarea':
                if (empty($value)) {
                    break;
                }
                $value = nl2br($this->addReadMore($value));
                break;
            case 'multiselect':
                $labels = [];
                foreach (explode(',', $value) as $optionId) {
                    if ($option = $this->getOptionValue($attribute, $optionId)) {
                        $labels[] = $option['label'];
                    }
                }
                $value = implode(', ', $labels);
                break;
            case 'select':
            case 'boolean':
                if ($value === '0' || $value === '') {
                    break;
                }
                $value = $this->getOptionValue($attribute, $value);
                break;
            default:
                break;
        }

        return $value;
    }

    protected function getOptionValue(\MageWorkshop\DetailedReview\Model\Attribute $attribute, $optionId)
    {
        $attributeOptions = $this->attributeHelper->getAttributeOptionValues($attribute);
        return isset($attributeOptions[$optionId])
            ? $attributeOptions[$optionId]
            : '';
    }

    /**
     * @return \MageWorkshop\Core\Helper\View
     */
    public function getViewHelper()
    {
        return $this->viewHelper;
    }

    /**
     * @param $value
     * @return string
     */
    public function addReadMore($value)
    {
        $html = '';
        $value = $this->escapeHtml(strip_tags($value));
        if (strlen($value) > self::QUANTITY_WORD_FOR_CUT) {
            // truncate string
            $teaser = substr($value, 0, self::QUANTITY_WORD_FOR_CUT);

            $html .= "<span class='teaser'>{$teaser}</span>";
            $html .= "<span class='completeDescription'>{$value}</span>";
            $html .= '<span class="moreLink" data-less="' . __('...less') . '" data-more="' . __('...read more') . '">' . __('...read more') . '</span>';

            return $html;
        }

        return $value;
    }
}
