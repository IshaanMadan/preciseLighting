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

namespace Webinse\RegionManager\Block\Adminhtml\System\Config\Buttons;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ImportStatesListButton extends GenericButton implements ButtonProviderInterface
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData([
                'id'        => 'import_states_list',
                'label'     => __('Import')
            ]);
        return $button->toHtml();
    }

    public function path()
    {
        return $this->getUrl('webinse_regionmanager/importStatesList/import');
    }

    public function getButtonData()
    {
        return [
            'label' => __('Import'),
            'class' => 'import primary',
            'on_click' => sprintf("location.href = '%s';", $this->path()),
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'import']],
                'form-role' => 'import',
            ],
            'sort_order' => 90,
        ];
    }
}