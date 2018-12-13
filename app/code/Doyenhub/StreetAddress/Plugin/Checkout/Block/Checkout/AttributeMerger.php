<?php
/**
 * Doyenhub_StreetAddress extension
 * NOTICE OF LICENSE
 * This source file is subject to the Doyenhub License
 * that is bundled with this package in the file LICENSE.txt.
 * @category  Doyenhub_Extensions
 * @package   Doyenhub_StreetAddress
 * @copyright Copyright (c) 2017
 * @author Doyenhub Developer <support@doyenhub.com>
 */
namespace Doyenhub\StreetAddress\Plugin\Checkout\Block\Checkout;

use Magento\Framework\App\ObjectManager;

class AttributeMerger {

    /**
     * merge street address placeholder
     * 
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $subject
     * @param object $result
     * @return $result parent class methods
     */
    public function afterMerge(\Magento\Checkout\Block\Checkout\AttributeMerger $subject, $result) {

        $arrFieldPlaceHolder = array(
            'city' => __('Town / City'),
            'telephone' => __('Phone number'),
            'postcode' => __('Postcode'),
            'company' => __('Company'),
            'firstname' => __('First name'),
            'lastname' => __('Last name'),
            'region_id' => __('State / Region'),
            'region' => __('State / Region'),
        );

        $arrActiveFields = array('country_id','region_id');

        $objectManager = ObjectManager::getInstance();
        $helper = $objectManager->get('Doyenhub\StreetAddress\Helper\Data');
        if ($helper->getStatus($helper->getStoreId())) {
            $placeholder = array_map('trim', explode('|', $helper->getPlaceholder($helper->getStoreId())));
            $i = 0;
            foreach ($placeholder as $data) {
                if (array_key_exists('street', $result)) {
                    $result['street']['children'][$i]['placeholder'] = __($data);
                    $result['street']['children'][$i]['label'] = __($data);
                    $i++;
                }
            }
        }

        if ($helper->getStatus($helper->getStoreId())) {
            foreach ($arrFieldPlaceHolder as $code => $label) {
                if (array_key_exists($code, $result)) {
                    $result[$code]['config']['placeholder'] = $label;
                    $result[$code]['config']['label'] = $label;
                    if($code == 'telephone'){
                        $result[$code]['config']['tooltip'] = false;
                    }
                }
            }

            foreach ($arrActiveFields as $code) {
                if (array_key_exists($code, $result)) {
                   $result[$code]['config']['additionalClasses'] = 'always-active-field';
                }
            }    
        }
        

        return $result;
    }

}
