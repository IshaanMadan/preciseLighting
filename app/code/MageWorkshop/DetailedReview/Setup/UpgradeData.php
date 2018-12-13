<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    const FLAG_CODE = 'mageworkshop_detailedreview_reserialization_status';
    /**
     * @var \MageWorkshop\Core\Helper\Serializer
     */
    private $serializer;

    /**
     * @var \MageWorkshop\DetailedReview\Model\ResourceModel\Attribute\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadataInterface;

    /**
     * @var \Magento\Framework\Flag\FlagResource
     */
    private $flagResource;

    /**
     * @var \Magento\Framework\FlagFactory
     */
    private $flagFactory;

    /**
     * UpgradeData constructor.
     * @param \MageWorkshop\Core\Helper\Serializer $serializer
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface
     * @param \MageWorkshop\DetailedReview\Model\ResourceModel\Attribute\CollectionFactory $collectionFactory
     * @param \Magento\Framework\FlagFactory $flagFactory
     * @param \Magento\Framework\Flag\FlagResource $flagResource
     */
    public function __construct(
        \MageWorkshop\Core\Helper\Serializer $serializer,
        \Magento\Framework\App\ProductMetadataInterface $productMetadataInterface,
        \MageWorkshop\DetailedReview\Model\ResourceModel\Attribute\CollectionFactory $collectionFactory,
        \Magento\Framework\FlagFactory $flagFactory,
        \Magento\Framework\Flag\FlagResource $flagResource
    ) {
        $this->serializer = $serializer;
        $this->collectionFactory = $collectionFactory;
        $this->productMetadataInterface = $productMetadataInterface;
        $this->flagResource = $flagResource;
        $this->flagFactory = $flagFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // Changes for compatibility with Magento v2.2+
        // Staring from this version the data is stored in JSON, not as a serialized PHP array
        // We can not use version comparison here,
        // because if the module is installed on Magento 2.2+ then this is not needed
        if (version_compare($this->productMetadataInterface->getVersion(), '2.2.0') >= 0
            && !$this->isDataReserialized()
        ) {
            $fieldsToReserialize = [
                \Magento\Eav\Api\Data\AttributeInterface::VALIDATE_RULES,
                'additional_data',
                'attribute_visual_settings'
            ];

            /**
             * @var \MageWorkshop\DetailedReview\Model\Attribute $attribute
             */
            foreach ($this->collectionFactory->create() as $attribute) {
                foreach ($fieldsToReserialize as $key) {
                    if (($storedData = $attribute->getData($key)) !== null) {
                        try {
                            $reserialized =
                                $this->serializer->serialize($this->serializer->unserialize($storedData));
                        } catch (\InvalidArgumentException $ex) {
                            $reserialized =
                                $this->serializer->serialize($this->serializer->unserializeNative($storedData));
                        }

                        $attribute->setData($key, $reserialized);
                    }
                }
                $attribute->save();
            }
            $this->setDataReserialized();
        }
    }

    /**
     * Return true if data reserialization has been run during magento upgrade from 2.1.x to 2.2.x
     * @return boolean
     */
    private function isDataReserialized()
    {
        return $this->getFlagObject()->getState();
    }

    /**
     * Set reserialized state to 1
     * @throws \Exception
     */
    private function setDataReserialized()
    {
        $this->getFlagObject()->setState(1)->save();
    }

    /**
     * Returns flag object.
     *
     * @return \Magento\Framework\Flag
     */
    private function getFlagObject()
    {
        /** @var \Magento\Framework\Flag $flag */
        $flag = $this->flagFactory
            ->create(['data' => ['flag_code' => self::FLAG_CODE]]);
        $this->flagResource->load($flag, self::FLAG_CODE, 'flag_code');
        return $flag;
    }
}
