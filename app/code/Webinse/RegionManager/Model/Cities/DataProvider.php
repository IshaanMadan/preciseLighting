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

namespace Webinse\RegionManager\Model\Cities;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Webinse\RegionManager\Model\ResourceModel\Cities\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $_loadedData;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->_loadedData[$item->getId()] = $item->getData();
        }
        return $this->_loadedData;
    }
}