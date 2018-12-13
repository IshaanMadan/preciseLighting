<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Indexer\Data\Product;

use Amasty\ElasticSearch\Api\Data\Indexer\Data\DataMapperInterface;

class ProductDataMapper implements DataMapperInterface
{
    const SKU_ATTRIBUTE = 'sku';

    /**
     * @var array
     */
    private $excludedAttributes = [
        'price',
        'media_gallery',
        'tier_price',
        'quantity_and_stock_status',
        'media_gallery',
        'giftcard_amounts'
    ];

    /**
     * @var AttributeDataProvider
     */
    private $attributeDataProvider;

    /**
     * @var string[]
     */
    private $attributesExcludedFromMerge = [
        'status',
        'visibility',
        'tax_class_id'
    ];

    public function __construct(AttributeDataProvider $attributeDataProvider, array $excludedAttributes = [])
    {
        $this->attributeDataProvider = $attributeDataProvider;
        $this->excludedAttributes = array_merge($this->excludedAttributes, $excludedAttributes);
    }

    /**
     * @param array $documentData
     * @param int $storeId
     * @param array $context
     * @return array
     */
    public function map(array $documentData, $storeId, array $context = [])
    {
        // reset attribute data for new store
        $documents = [];
        foreach ($documentData as $productId => $indexData) {
            $document = ['store_id' => $storeId];
            $productIndexData = $this->getProductData($productId, $indexData, $storeId);
            foreach ($productIndexData as $attributeCode => $value) {
                if (in_array($attributeCode, $this->excludedAttributes, true)) {
                    continue;
                }
                $document[$attributeCode] = $value;
            }
            $documents[$productId] = $document;
        }

        return $documents;
    }

    /**
     * @param int $productId
     * @param array $indexData
     * @param int $storeId
     * @return array
     */
    private function getProductData($productId, array $indexData, $storeId)
    {
        $productAttributes = [];
        foreach ($indexData as $attributeId => $attributeValue) {
            $attribute = $this->attributeDataProvider->getAttribute($attributeId);
            if (!$attribute) {
                continue;
            }
            $productAttributes = array_merge(
                $productAttributes,
                $this->prepareProductData(
                    $productId,
                    $attributeValue,
                    $attribute
                )
            );
        }

        return $productAttributes;
    }

    /**
     * @param int $productId
     * @param mixed $attributeValue
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @return array
     */
    public function prepareProductData(
        $productId,
        $attributeValue,
        \Magento\Eav\Model\Entity\Attribute $attribute
    ) {
        $productAttributes = [];
        $attributeCode = $attribute->getAttributeCode();
        $attributeFrontendInput = $attribute->getFrontendInput();
        if (is_array($attributeValue)) {
            $value = $this->getProductAttributeValue($productId, $attribute, $attributeValue);
            if (in_array($attributeFrontendInput, ['select', 'multiselect'], true)) {
                $productAttributes = $this->prepareOptionalAttributeValues($attribute, $attributeValue);
                if ($attributeFrontendInput == 'select') {
                    $productAttributes[$attributeCode] = $value;
                }
                return $productAttributes;
            }
        } else {
            $value = $attributeValue;
        }

        if ($value) {
            $productAttributes[$attributeCode] = $value;
            if (!is_array($value)) {
                $attributeOptions = $this->getAttributeOptions($attribute);
                if (isset($attributeOptions[$value])) {
                    $productAttributes[$attributeCode . '_value'] = $attributeOptions[$value];
                }

                if ($attributeFrontendInput === 'date'
                    || in_array($attribute->getBackendType(), ['datetime', 'timestamp'], true)
                ) {
                    if (preg_replace('#[ 0:-]#', '', $value) !== '') {
                        $dateObj = new \DateTime($value, new \DateTimeZone('UTC'));
                        $productAttributes[$attributeCode] = $dateObj->format('c');
                    }
                }
            }
        }

        return $productAttributes;
    }

    /**
     * @param int $productId
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @param array $attributeValue
     * @return mixed
     */
    private function getProductAttributeValue($productId, $attribute, array $attributeValue)
    {
        if ((!$attribute->getIsSearchable() || $this->isAttributeExcludedFromMerge($attribute))
            && isset($attributeValue[$productId])
        ) {
            return $attributeValue[$productId];
        }

        if (in_array($attribute->getFrontendInput(), ['text', 'textarea'], true)) {
            $attributeValue = [array_shift($attributeValue)];
        }

        return array_values(array_unique($attributeValue));
    }

    /**
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @return bool
     */
    private function isAttributeExcludedFromMerge(\Magento\Eav\Model\Entity\Attribute $attribute)
    {
        $result = false;
        if (in_array($attribute->getAttributeCode(), $this->attributesExcludedFromMerge, true)
            || in_array($attribute->getFrontendInput(), ['text', 'textarea'], true)) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @param mixed $attributeValue
     * @return array
     */
    private function prepareOptionalAttributeValues(\Magento\Eav\Model\Entity\Attribute $attribute, $attributeValue)
    {
        /**
         * @TODO add prices mapper
         */
        $productAttributes = [];
        $attributeCode = $attribute->getAttributeCode();
        $attributeFrontendInput = $attribute->getFrontendInput();
        $attributeOptions = $this->getAttributeOptions($attribute);
        $selectedValues = [];
        if ($attributeFrontendInput == 'select' && $attribute->getIsSearchable()) {
            foreach ($attributeValue as $selectedValue) {
                if (isset($attributeOptions[$selectedValue])) {
                    $selectedValues[] = $attributeOptions[$selectedValue];
                }
            }
        } elseif ($attributeFrontendInput == 'multiselect') {
            $value = [];
            foreach ($attributeValue as $selectedAttributeValues) {
                $selectedAttributeValues = explode(',', $selectedAttributeValues);
                foreach ($selectedAttributeValues as $selectedValue) {
                    if (isset($attributeOptions[$selectedValue])) {
                        if ($attribute->getIsSearchable()) {
                            $selectedValues[] = $attributeOptions[$selectedValue];
                        }
                        $value[] = $selectedValue;
                    }
                }
            }
            $productAttributes[$attributeCode] = array_unique($value);
        }
        $selectedValues = array_unique($selectedValues);
        if (!empty($selectedValues)) {
            $productAttributes[$attributeCode . '_value'] = array_values($selectedValues);
        }

        return $productAttributes;
    }

    /**
     * @param \Magento\Eav\Model\Entity\Attribute $attribute
     * @return array
     */
    public function getAttributeOptions(\Magento\Eav\Model\Entity\Attribute $attribute)
    {
        return $attribute->getAttributeOptionsArray();
    }
}
