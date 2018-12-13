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

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Webinse\RegionManager\Model\Source\StateOptions;
use Webinse\RegionManager\Model\Config;

/**
 * Class LayoutProcessor
 * @package Vendor\Module\Block\Checkout
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @var DirectoryHelper
     */
    protected $directoryHelper;
    /**
     * @var StateOptions
     */
    protected $_stateOption;
    /**
     * @var Config
     */
    protected $_config;

    /**
     * LayoutProcessor constructor.
     * @param StateOptions $stateOption
     * @param Config $config
     * @param DirectoryHelper $directoryHelper
     */
    public function __construct(
        StateOptions $stateOption,
        Config $config,
        DirectoryHelper $directoryHelper
    ) {
        $this->directoryHelper = $directoryHelper;
        $this->_stateOption    = $stateOption;
        $this->_config         = $config;
    }

    /**
     * @param array $result
     * @return array
     */
    public function process($result)
    {
        if ($this->_config->getEnableExtensionYesNo())

        if ($result['components']['checkout']['children']['steps']
        ['children']['shipping-step']['children']['shippingAddress'])
        {

            $shippingAddressFieldSet = $result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

            $region = $this->_stateOption->getStates();
            $regionOptions[] = ['label' => 'Please Select..', 'value' => ''];
            foreach ($region as $field) {
                $regionOptions[] = ['label' => $field['states_name'], 'value' => $field['states_name']];
            }

            $shippingAddressFieldSet['region_id'] = '';
            $shippingAddressFieldSet['region'] = '';
            $result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $shippingAddressFieldSet;
            $result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region'] = [
                'component' => 'Magento_Ui/js/form/element/select',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/select',
                    'id' => 'drop-down',
                    'additionalClasses' => 'state-drop-down',
                ],
                'dataScope' => 'shippingAddress.region',
                'label' => 'State/Province',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => ['required-entry' => true],
                'sortOrder' => 75,
                'id' => 'state-drop-down',
                'options' => $regionOptions
            ];

            $result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = [
                'component' => 'Magento_Ui/js/form/element/select',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/select',
                    'id' => 'drop-down',
                    'additionalClasses' => 'city-drop-down',
                ],
                'dataScope' => 'shippingAddress.city',
                'label' => 'City',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => ['required-entry' => true],
                'sortOrder' => 80,
                'id' => 'city-drop-down',
                'options' => [
                    [
                        'value' => '',
                        'label' => 'Please select...',
                    ]
                ]
            ];

            $result['components']['checkout']['children']['steps']
            ['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode'] = [
                'component' => 'Magento_Ui/js/form/element/select',
                'config' => [
                    'customScope' => 'shippingAddress',
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/select',
                    'id' => 'drop-down',
                    'additionalClasses' => 'postcode-drop-down',
                ],
                'dataScope' => 'shippingAddress.postcode',
                'label' => 'Zip/Postal Code ',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => ['required-entry' => true],
                'sortOrder' => 85,
                'id' => 'postcode-drop-down',
                'options' => [
                    [
                        'value' => '',
                        'label' => 'Please select...',
                    ]
                ]
            ];

        }
        return $result;
    }
}