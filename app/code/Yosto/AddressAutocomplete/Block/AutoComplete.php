<?php
/**
 * Created by PhpStorm.
 * User: nghiata
 * Date: 28/06/2017
 * Time: 17:02
 */

namespace Yosto\AddressAutocomplete\Block;


use Magento\Framework\View\Element\Template;
use Yosto\AddressAutocomplete\Helper\ConfigData;

class AutoComplete extends Template
{
    protected $_helper;

    public function __construct(
        Template\Context $context,
        array $data = [],
        ConfigData $helper
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function isEnable()
    {
        return (bool) $this->_helper->isEnabled();
    }

    public function getApiKey()
    {
        return $this->_helper->getApiKey();
    }
}