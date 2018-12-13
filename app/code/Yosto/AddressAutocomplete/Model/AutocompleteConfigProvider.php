<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */


namespace Yosto\AddressAutocomplete\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class AutocompleteConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Yosto\AddressAutocomplete\Helper\ConfigData
     */
    private $helper;

    /**
     * @param \Yosto\AddressAutocomplete\Helper\ConfigData $helper
     */
    public function __construct(
        \Yosto\AddressAutocomplete\Helper\ConfigData $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config['yosto_autocomplete'] = [
            'active'        => $this->helper->isEnabled()
        ];
        return $config;
    }
}
