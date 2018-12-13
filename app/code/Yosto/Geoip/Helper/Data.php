<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Geoip\Helper;

use GeoIp2\Database\Reader;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Yosto\Geoip\Model\ResourceModel\LocationDirectory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
// use Psr\Log\LoggerInterface as Logger;
use GeoIp2\Exception\GeoIp2Exception;

class Data
{
    const DATABASE_NAME_CONFIG = 'geoip/general/filename';
    const ENABLE_CONFIG   = 'geoip/general/enable';
    const DATABASE_PATH_CONFIG = 'geoip/general/database_path';
    const ENABLE_TESTING_CONFIG = 'geoip/testing/enable';
    const FIXED_IP_CONFIG = 'geoip/testing/fixed_ip';

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    protected $regionFactory;

    /**
     * @var LocationDirectory
     */
    private $locationDirectory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param RemoteAddress $remoteAddress
     * @param RegionInterfaceFactory $regionFactory
     * @param LocationDirectory $locationDirectory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        RemoteAddress $remoteAddress,
        RegionInterfaceFactory $regionFactory,
        LocationDirectory $locationDirectory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->regionFactory = $regionFactory;
        $this->locationDirectory = $locationDirectory;
        $this->scopeConfig = $scopeConfig;
    }

    public function getGeo2($ip = null)
    {
        $enable = $this->isModuleEnabled();
        if (!$enable) {
            return;
        }

        $data = array();
        if (empty($ip)) {
            $ip = $this->remoteAddress->getRemoteAddress();

            if ($this->isEnableTesting()) {
                if ($this->getFixedIp() != null) {
                    $ip = $this->getFixedIp();
                }
            }
        }
        if ($ip) {
            $filename = $this->getDatabaseName();
            $databasePath = $this->getDatabasePath();
            try {
                $mmdb = getcwd() . $databasePath . basename($filename);
                if (!file_exists($mmdb)) {
                    return $data;
                }
                $reader = new Reader($mmdb);
                $record = $reader->city($ip);
            } catch (GeoIp2Exception $e) {
                return $data;
            }

            $regionId = 0;
            $countryCode = $record->country->isoCode;
            $regionCode = $record->mostSpecificSubdivision->isoCode;
            if ($this->locationDirectory->hasCountryId($countryCode)) {
                $countryId = $this->locationDirectory->getCountryId($countryCode);
            }
            if ($this->locationDirectory->hasRegionId($countryId, $regionCode)) {
                $regionId = $this->locationDirectory->getRegionId($countryId, $regionCode);
            }
            $region = $this->regionFactory->create()
                ->setRegionCode($regionCode)
                ->setRegionId($regionId)
            ;

            $data = array(
                'city'       => $record->city->name,
                'region'     => $region,
                'region_id'  => $regionId,
                'postcode'   => $record->postal->code,
                'country_id' => $record->country->isoCode
            );
        }
        return $data;
    }

    public function isModuleEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_CONFIG, ScopeInterface::SCOPE_STORE);
    }

    public function isEnableTesting()
    {
        return (bool) $this->scopeConfig->getValue(self::ENABLE_TESTING_CONFIG, ScopeInterface::SCOPE_STORE);
    }

    public function getFixedIp()
    {
        return $this->scopeConfig->getValue(self::FIXED_IP_CONFIG, ScopeInterface::SCOPE_STORE);
    }
    public function getDatabasePath()
    {
        return $this->scopeConfig->getValue(self::DATABASE_PATH_CONFIG, ScopeInterface::SCOPE_STORE);
    }

    public function getDatabaseName()
    {
        return $this->scopeConfig->getValue(self::DATABASE_NAME_CONFIG, ScopeInterface::SCOPE_STORE);
    }
}
