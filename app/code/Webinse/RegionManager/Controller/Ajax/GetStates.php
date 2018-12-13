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
use Webinse\RegionManager\Model\ResourceModel\States\CollectionFactory as StatesCollection;
use Webinse\RegionManager\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\Page;

class GetStates extends Action\Action
{
    /**
     * @var JsonFactory
     */
    public $_resultJsonFactory;
    /**
     * @var StatesCollection
     */
    protected $_statesCollection;
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
     * @param StatesCollection $statesCollection
     * @param Config $config
     * @param Product $product
     * @param Page $page
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Action\Context $context,
        StatesCollection $statesCollection,
        Config $config,
        Product $product,
        Page $page,
        JsonFactory $resultJsonFactory
    )
    {
        $this->_resultJsonFactory   = $resultJsonFactory;
        $this->_statesCollection    = $statesCollection;
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
            $collection = $this->_statesCollection->create()
                ->setOrder('states_name','ASC')
                ->getData();
            return (!empty($collection)) ? $result->setData(['request' => 'OK', 'result' => $collection]) : $result->setData(['request' => 'No States!', 'result' => 'No States!']);
        }
        else
        {
            return $result->setData(['request' => 'AJAX ERROR']);
        }
    }
}