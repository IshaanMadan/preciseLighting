<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Yosto\Storepickup\Helper\Data;
use Yosto\Storepickup\Model\ResourceModel\Location\CollectionFactory;

/**
 * Class StorepickupConfigProvider
 * @package Yosto\Storepickup\Model
 */
class StorepickupConfigProvider implements ConfigProviderInterface
{
    const XPATH_FORMAT = 'carriers/storepickup/format';
    const XPATH_DISABLED = 'carriers/storepickup/disabled';
    const XPATH_HOURMIN = 'carriers/storepickup/hourMin';
    const XPATH_HOURMAX = 'carriers/storepickup/hourMax';
    const XPATH_ACTIVE = 'carriers/storepickup/active';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    protected $_helper;

    protected $_locationCollectionFactory;

    /**
     * StorepickupConfigProvider constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param CollectionFactory $locationCollectionFactory
     * @param Data $helper
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CollectionFactory $locationCollectionFactory,
        Data $helper
    ) {

        $this->storeManager = $storeManager;
        $this->_locationCollectionFactory = $locationCollectionFactory;
        $this->_helper = $helper;

    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $store = $this->getStoreId();
        /**
         * Config for pickup_date
         */
        $disabled = $this->_helper->getConfig(self::XPATH_DISABLED, $store);
        $hourMin = $this->_helper->getConfig(self::XPATH_HOURMIN, $store);
        $hourMax = $this->_helper->getConfig(self::XPATH_HOURMAX, $store);
        $format = $this->_helper->getConfig(self::XPATH_FORMAT, $store);
        /**
         * General config
         */

        $isActive = $this->_helper->getConfig(
            self::XPATH_ACTIVE,
            $store
        );

        $noday = 0;
        if ($disabled == -1) {
            $noday = 1;
        }
        $stores = [];

        foreach ($this->getStore() as $item) {
            $item->setData('coordinate', $item->getData('latitude') . ',' . $item->getData('longitude'));
            $stores[] = $item->getData();

        }

        //$markers = $this->getMarkers();
        $config = [
            'yosto_storepickup' => [
                'active' => $isActive,
                'stores' => $stores
            ],
            'shipping' => [
                'pickup_date' => [
                    'format' => $format,
                    'disabled' => $disabled,
                    'noday' => $noday,
                    'hourMin' => $hourMin,
                    'hourMax' => $hourMax
                ]
            ]
        ];

        return $config;
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }


    protected function _getStoreCollection()
    {
        $collection = $this->_locationCollectionFactory->create()->load();

        return $collection;
    }


    public function getStore()
    {
        return $this->_getStoreCollection()
            ->addFieldToSelect('location_id')
            ->addFieldToSelect('store_title')
            ->addFieldToSelect('latitude')
            ->addFieldToSelect('longitude');
    }
}
