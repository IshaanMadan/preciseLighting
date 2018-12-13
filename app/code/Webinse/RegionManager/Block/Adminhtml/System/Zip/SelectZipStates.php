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

namespace Webinse\RegionManager\Block\Adminhtml\System\Zip;

use Webinse\RegionManager\Model\ResourceModel\States\CollectionFactory as StatesFactory;

class SelectZipStates extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var StatesFactory
     */
    protected $_statesCollection;

    /**
     * SelectCities constructor.
     * @param StatesFactory $statesCollection
     * @param array $data
     */
    public function __construct(
        StatesFactory $statesCollection,
        array $data = []
    )
    {
        $this->_statesCollection    = $statesCollection;
        parent::__construct($data);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getArray();
    }

    /**
     * @return array
     */
    public function getArray()
    {
        $collection = $this->_statesCollection->create()->getData();
        $arr = [['label' => 'Please Select...' ,'value' => '', 'attr' => 'selected']];
        foreach ($collection as $state)
        {
            $arr[] = ['label' => __($state['states_name']) ,'value' => $state['states_name']];
        }
        return $arr;
    }
}