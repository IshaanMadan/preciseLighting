<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Form;

use Magento\Framework\Exception\LocalizedException;

class Validate extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractForm
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $response = [
            'error' => false
        ];

        try {
            $this->validate();
        } catch (\Exception $e) {
            $response = [
                'error'   => true,
                'message' => $e->getMessage()
            ];
        }

        $responseObject = new \Magento\Framework\DataObject();
        return $this->resultJsonFactory->create()
            ->setJsonData($responseObject->setData($response)->toJson());
    }

    /**
     * Validate attribute set data
     *
     * @throws \Exception
     */
    protected function validate()
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();

        if ($attributeSetId = $request->getParam('form_id')) {
            if (!$attribute = $this->attributeHelper->getAttributeSet($attributeSetId)) {
                throw new LocalizedException(__(self::FORM_NO_LONGER_EXISTS_EXCEPTION));
            }

            if ($attribute->getEntityTypeId() != $this->getEntityTypeId()) {
                throw new LocalizedException(__(self::INVALID_ENTITY_TYPE_EXCEPTION));
            }
        }

        if (!$attributeSetName = $request->getParam('attribute_set_name')) {
            throw new LocalizedException(__(self::FORM_NAME_MISSED_EXCEPTION));
        }

        /** @var \Magento\Eav\Api\Data\AttributeSetInterface $potentialDuplicate */
        foreach ($this->attributeHelper->getAttributeSetsByName($attributeSetName) as $potentialDuplicate) {
            if ($potentialDuplicate->getAttributeSetName() == $attributeSetName) {
                if (!$attributeSetId || ($attributeSetId != $potentialDuplicate->getAttributeSetId())) {
                    throw new LocalizedException(__(self::FORM_NAME_EXISTS_EXCEPTION));
                }
            }
        }
    }
}
