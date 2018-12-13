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

namespace Webinse\RegionManager\Controller\Ajax;

use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Webinse\RegionManager\Model\ResourceModel\Cities\CollectionFactory as CitiesCollection;
use Webinse\RegionManager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class GetCities extends Action\Action
{
    /**
     * @var JsonFactory
     */
    public $_resultJsonFactory;
    /**
     * @var CitiesCollection
     */
    protected $_citiesCollection;
    /**
     * @var Config
     */
    protected $_config;
    /**
     * @var Product
     */
    protected $_product;
    /**
     * @var Page
     */
    protected $_page;

    /**
     * GetTags constructor.
     * @param Action\Context $context
     * @param CitiesCollection $citiesCollection
     * @param Config $config
     * @param Product $product
     * @param Page $page
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        CitiesCollection $citiesCollection,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_citiesCollection    = $citiesCollection;
        $this->_config              = $config;
        $this->_product             = $product;
        $this->_page                = $page;
        return parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        if ($this->getRequest()->isAjax())
        {
            $post = $this->getRequest()->getParam('selected_state');

            $collection = $this->_citiesCollection->create()
                ->addFieldToFilter('states_name',$post)
                ->setOrder('cities_name','ASC')
                ->getData();
            return (!empty($collection)) ? $result->setData(['request' => 'OK', 'result' => $collection]) : $result->setData(['request' => 'No Cities!', 'result' => 'No Cities!']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}