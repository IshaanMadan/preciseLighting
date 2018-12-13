<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Block;

use Magento\Framework\View\Element\Template;
use Yosto\Storepickup\Helper\Data;
use Yosto\Storepickup\Model\ResourceModel\Location\CollectionFactory as LocationCollectionFactory;

/**
 * Class Stores
 * @package Yosto\Storepickup\Block
 */
class Stores extends \Magento\Framework\View\Element\Template
{
    /**
     * @var LocationCollectionFactory
     */
    protected $_locationCollectionFactory;
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * Stores constructor.
     * @param LocationCollectionFactory $locationCollectionFactory
     * @param Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        LocationCollectionFactory $locationCollectionFactory,
        Data $helper,
        Template\Context $context,
        array $data = [])
    {
        $this->_locationCollectionFactory = $locationCollectionFactory;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    protected function _getLocationCollection()
    {
        $collection = $this->_locationCollectionFactory->create()->load();
                        
        return $collection;
    }


    /**
     * @return mixed
     */
    public function getLocationCollection()
    {
        //search by country state city
        $cities = $this->getCities();
        $cityTmp = [];
        foreach ($cities as $_city) {
            $cityTmp[] = $_city;
        }
        
        $data = $this->getRequest()->getPost();
        $country = '';
        $state = '';
        $city = $cityTmp[0]->getCity();
        $store = '';
        if (!empty($data)) {
            if (isset($data['country'])) {
                $country = $data['country'];
            }
            
            if (isset($data['state'])) {
                $state = $data['state'];
            }
            
            if (isset($data['city_select'])) {
                $city = $data['city_select'];
            }
        }
        
        if (is_null($this->_locationCollection)) {
            $this->_locationCollection = $this->_getLocationCollection();
            $this->_locationCollection->prepareForList($this->getCurrentPage());
            if (!empty($country)) {
                $this->_locationCollection->addFieldToFilter('country', ['like'=>$country]);
            }
            
            //search by state
            if (!empty($state)) {
                $this->_locationCollection->addFieldToFilter('state', ['like'=>$state]);
            }
            
            //search by city
            if (!empty($city)) {
                $this->_locationCollection->addFieldToFilter('city', ['like'=>$city]);
            }
        }
        return $this->_locationCollection;
    }
    
    public function getCities()
    {
        //get city
        return $this->_getLocationCollection()
            ->addFieldToSelect('city')
            ->distinct(true);
    }
    
    public function getCountries()
    {
        return $this->_getLocationCollection()
            ->addFieldToSelect('country')
            ->distinct(true);
    }
    
    public function getStores()
    {
        //get store data
        $data = $this->getRequest()->getPost();
        $city = '';
        $this->_locationCollection = $this->_getLocationCollection();
        if (!empty($data)) {
            if (isset($data['city_select'])) {
                $city = $data['city_select'];
            }
        }
        if (!empty($city)) {
                $this->_locationCollection->addFieldToFilter('city', ['like'=>$city]);
        }
        return $this->_locationCollection->addFieldToSelect('store_title');
    }

    public function isEnableModule()
    {
        return $this->_helper->getConfig('carriers/storepickup/active');
    }
    public function getMarkerImage()
    {
        return $this->_helper->getConfig('carriers/storepickup/upload_image_id');
    }
    public function getZoomLevel()
    {
        return $this->_helper->getConfig('carriers/storepickup/zoom_level');
    }

    public function getMarkerImageUrl()
    {
        $currentStore = $this->_storeManager->getStore();
        $markerImageUrl = '';
        $markerImage = $this->getMarkerImage();
        if ($markerImage) {
            $markerImageUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'marker/' . $markerImage;
        }
        return $markerImageUrl;
    }
    public function getStoreLocations()
    {
        return $this->_locationCollectionFactory->create()->addFieldToFilter('is_enable', 1)->load();
    }
    public function getCurrentStoreBaseUrl()
    {
        $currentStore = $this->_storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }


    public function getMarkersData() {
        $storeLocations = $this->getStoreLocations();
        if (sizeof($storeLocations)) {
            $currentStoreBaseUrl = $this->getCurrentStoreBaseUrl();
            $i = 0;
            $tmp = [];
            $markers = [];
            foreach ($storeLocations as $store) {
                $store_title = $store->getStoreTitle();
                $storeLat = $store->getLatitude();
                $storeLong = $store->getLongitude();

                if ($store->getimage()) {
                    $storeImage = $currentStoreBaseUrl . $store->getimage();
                } else {
                    $storeImage = '';
                }

                $latLong = $storeLat . "," . $storeLong;
                $info = $store_title . ',#Address:' . $store->getAddress() .
                    ',#Phone:' . $store->getPhone() . ',#Email:' . $store->getEmail() . ',#' . $storeImage;

                $tmpCountry[$store->getCountry()][$store->getCity()][$store->getId()][$latLong] = $info;
                $tmp[$store->getCity()][$store->getId()][$latLong] = $info;

                $markers[$i]['store_title'] = $store->getStoreTitle() . ',Address: ' . $store->getAddress() .
                    ',Phone: ' . $store->getPhone() . ',Email: ' . $store->getEmail() . ',' . $storeImage;
                $markers[$i]['lat'] = $storeLat;
                $markers[$i]['long'] = $storeLong;
                $i++;
            }


            $newMarkers[$latLong] = $info;
            $newMarkerData = json_encode($latLong);
            $jsonCountryArray = json_encode($tmpCountry);
            $jsonArray = json_encode($tmp);

            return [
                'markers' => $markers,
                'newMarkers' => $newMarkers,
                'newMarkerData' => $newMarkerData,
                'jsonCountryArray' => $jsonCountryArray,
                "jsonArray" => $jsonArray
            ];
        } else {
            return false;
        }

    }
}
