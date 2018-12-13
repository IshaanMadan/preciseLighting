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


namespace Webinse\RegionManager\Block\Checkout;

use Magento\Framework\View\Element\Template;
use Webinse\RegionManager\Model\Config;


class Js extends \Magento\Framework\View\Element\Template
{
    protected $_config;

    public function __construct(
        Template\Context $context,
        Config $config,
        array $data = []
    )
    {
        $this->_config = $config;
        parent::__construct($context, $data);
    }

    public function enableModule()
    {
        return $this->_config->getEnableExtensionYesNo() == 1 ? true : false;
    }

    public function enableButtons()
    {
        return $this->_config->getEnableButtonsYesNo() == 1 ? true : false;
    }

}