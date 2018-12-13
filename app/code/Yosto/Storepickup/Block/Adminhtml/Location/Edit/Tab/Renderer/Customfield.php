<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Block\Adminhtml\Location\Edit\Tab\Renderer;
 
use Yosto\Storepickup\Helper\Data;

class Customfield extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    protected $_helper;

    protected $_template = 'Yosto_Storepickup::customfield.phtml';

    public function __construct(
        Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }


    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
         $this->_element = $element;
         $html = $this->toHtml();
         return $html;
    }

    public function getApiKey() {
       return $this->_helper->getConfig('yosto_opc_checkout/general/google_api_key');
    }

    public function getMarkerImageUrl()
    {
        $markerImageUrl = '';
        $markerImage        = $this->_helper->getConfig('carriers/storepickup/upload_image_id');
        $currentStore     = $this->_storeManager->getStore();
        if ($markerImage) {
            return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                .'marker/'
                .$markerImage;
        } else {
            return $markerImageUrl;
        }
    }

    public function getDefaultLat()
    {
        return $this->_helper->getConfig('carriers/storepickup/default_lat');
    }
    public function getDefaultLong()
    {
        return $this->_helper->getConfig('carriers/storepickup/default_long');
    }

}
