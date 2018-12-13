<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Geoip\Block\Checkout;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Helper\Address as AddressHelper;

use Yosto\Geoip\Helper\Data as GeoHelper;

/**
 * Class AttributeMerger
 * @package Yosto\Geoip\Block\Checkout
 */
class AttributeMerger extends \Magento\Checkout\Block\Checkout\AttributeMerger
{
    /**
     *
     * @var array
     */
    protected $geoData = [];

    /**
     * AttributeMerger constructor.
     * @param AddressHelper $addressHelper
     * @param Session $customerSession
     * @param CustomerRepository $customerRepository
     * @param DirectoryHelper $directoryHelper
     * @param GeoHelper $geoHelper
     */
    public function __construct(
        AddressHelper $addressHelper,
        Session $customerSession,
        CustomerRepository $customerRepository,
        DirectoryHelper $directoryHelper,
        GeoHelper $geoHelper
    ) {
        parent::__construct($addressHelper, $customerSession, $customerRepository, $directoryHelper);

        $this->geoData = $geoHelper->getGeo2();
    }

    /**
     * @param string $attributeCode
     * @return mixed|null|string
     */
    protected function getDefaultValue($attributeCode)
    {
        $return = parent::getDefaultValue($attributeCode);
        $geoData = $this->geoData;

        if (!empty($geoData)) {
            switch ($attributeCode) {
                case 'city':
                    return isset($geoData['city']) ? $geoData['city'] : null;
                case 'region_id':
                    return isset($geoData['region_id']) ? $geoData['region_id'] : null;
                case 'country_id':
                    return isset($geoData['country_id']) ? $geoData['country_id'] : null;
                case 'postcode':
                    return isset($geoData['postcode']) ? $geoData['postcode'] : null;
            }
        }
        return $return;
    }
}
