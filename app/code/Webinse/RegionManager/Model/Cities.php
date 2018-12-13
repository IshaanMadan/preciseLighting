<?php
/**
​ * ​ ​ Webinse
​ *
​ * ​ ​ PHP​ ​ Version​ ​ 7.0.22
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */
/**
​ * ​ ​ Comment​ ​ for​ ​ file
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */
namespace Webinse\RegionManager\Model;

use Magento\Framework\Model\AbstractModel;
use Webinse\RegionManager\Api\Data\CitiesInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Cities extends AbstractModel implements CitiesInterface, IdentityInterface
{
    const CACHE_TAG = 'webinse_regionmanager_cities';

    protected $_cacheTag = 'webinse_regionmanager_cities';

    protected $_eventPrefix = 'webinse_regionmanager_cities';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Webinse\RegionManager\Model\ResourceModel\Cities');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * @return mixed
     */
    public function getStatesName()
    {
        return $this->getData(self::STATES_NAME);
    }

    /**
     * @return mixed
     */
    public function getCitiesName()
    {
        return $this->getData(self::CITIES_NAME);
    }


    /**
     * @param mixed $id
     * @return $this|mixed
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @param $states_name
     * @return $this|mixed
     */
    public function setStatesName($states_name)
    {
        return $this->setData(self::STATES_NAME, $states_name);
    }

    /**
     * @param $cities_name
     * @return $this|mixed
     */
    public function setCitiesName($cities_name)
    {
        return $this->setData(self::CITIES_NAME, $cities_name);
    }
}