<?php
/**
 * Copyright © 2018 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace MageWorx\OptionFeatures\Model\Attribute\OptionValue;

use Magento\Framework\App\ResourceConnection;
use MageWorx\OptionFeatures\Helper\Data as Helper;
use MageWorx\OptionBase\Helper\System as SystemHelper;
use MageWorx\OptionBase\Model\AttributeInterface;
use MageWorx\OptionFeatures\Model\OptionTypeIsDefault;
use MageWorx\OptionFeatures\Model\ResourceModel\OptionTypeIsDefault\Collection as IsDefaultCollection;
use MageWorx\OptionFeatures\Model\OptionTypeIsDefaultFactory as IsDefaultFactory;

class IsDefault implements AttributeInterface
{
    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var SystemHelper
     */
    protected $systemHelper;

    /**
     * @var IsDefaultFactory
     */
    protected $isDefaultFactory;

    /**
     * @var IsDefaultCollection
     */
    protected $isDefaultCollection;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @param ResourceConnection $resource
     * @param IsDefaultFactory $isDefaultFactory
     * @param IsDefaultCollection $isDefaultCollection
     * @param Helper $helper
     * @param SystemHelper $systemHelper
     */
    public function __construct(
        ResourceConnection $resource,
        IsDefaultFactory $isDefaultFactory,
        IsDefaultCollection $isDefaultCollection,
        Helper $helper,
        SystemHelper $systemHelper
    ) {
        $this->resource = $resource;
        $this->helper = $helper;
        $this->systemHelper = $systemHelper;
        $this->isDefaultFactory = $isDefaultFactory;
        $this->isDefaultCollection = $isDefaultCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Helper::KEY_IS_DEFAULT;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableName()
    {
        $map = [
            'product' => OptionTypeIsDefault::TABLE_NAME,
            'group' => OptionTypeIsDefault::OPTIONTEMPLATES_TABLE_NAME
        ];
        return $map[$this->entity->getType()];
    }

    /**
     * {@inheritdoc}
     */
    public function clearData()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function applyData($entity, $options)
    {
        $this->entity = $entity;

        $isDefaults = [];
        foreach ($options as $option) {
            if (empty($option['values'])) {
                continue;
            }
            if ($option['type'] == 'radio' || $option['type'] == 'drop_down') {
                $isDefaultValueAlreadySelected = false;
                foreach ($option['values'] as $value) {
                    if ($value[$this->getName()] == 1 && !$isDefaultValueAlreadySelected) {
                        $isDefaults[$value['mageworx_option_type_id']] = $value[$this->getName()];
                        $isDefaultValueAlreadySelected = true;
                    } else {
                        $isDefaults[$value['mageworx_option_type_id']] = 0;
                    }
                }
            } else {
                foreach ($option['values'] as $value) {
                    $isDefaults[$value['mageworx_option_type_id']] = $value[$this->getName()];
                }
            }
        }

        $this->saveDefaults($isDefaults);
    }

    /**
     * Save defaults
     *
     * @param $items
     * @return void
     */
    protected function saveDefaults($items)
    {
        $storeId = $this->systemHelper->resolveCurrentStoreId();
        foreach ($items as $itemKey => $itemValue) {
            $this->deleteOldDefaults($itemKey, $storeId);

            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName($this->getTableName($this->entity->getType()));
            $data = [
                'mageworx_option_type_id' => $itemKey,
                'store_id' => $storeId,
                $this->getName() => $itemValue ? $itemValue : 0,
            ];
            $connection->insert($tableName, $data);
        }
    }

    /**
     * Delete old option value defaults
     *
     * @param $mageworxOptionTypeId
     * @param $storeId
     * @return void
     */
    protected function deleteOldDefaults($mageworxOptionTypeId, $storeId)
    {
        $connection = $this->resource->getConnection();
        $tableName = $this->resource->getTableName($this->getTableName());

        if ($this->entity->getType() == 'product') {
            $select = $connection->select()
                ->reset()
                ->from(['defaults' => $tableName])
                ->joinLeft(
                    ['cpotv' => $this->resource->getTableName('catalog_product_option_type_value')],
                    'cpotv.mageworx_option_type_id = defaults.mageworx_option_type_id',
                    []
                )
                ->where('cpotv.option_id IS NULL');
            $sql = $select->deleteFromSelect('defaults');
            $connection->query($sql);
        }

        $connection->delete(
            $tableName,
            [
                'mageworx_option_type_id = ?' => $mageworxOptionTypeId,
                'store_id  = ?' => $storeId,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function prepareData($object)
    {
        return $object->getData($this->getName());
    }
}
