<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magento\Customer\Model\Customer as CustomerModel;
use Magenest\QuickBooksDesktop\Model\QBXML;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class Customer extends QBXML
{
    /**
     * @var
     */
    protected $_customer;

    /**
     * Customer constructor.
     *
     * @param CustomerModel $customer
     */
    public function __construct(
        CustomerModel $customer
    ) {
        $this->_customer = $customer;
    }

    /**
     * Get XML using sync to QBD
     *
     * @param  int $id
     * @return string
     */
    public function getXml($id)
    {
        /** @var \Magento\Customer\Model\Customer $model */
        $model = $this->_customer->load($id);
        $billAddress = $model->getDefaultBillingAddress();
        $shipAddress = $model->getDefaultShippingAddress();

        $xml = '<Name>'.$model->getName().'</Name>';
        $xml .= $billAddress ? '<CompanyName>' .$billAddress->getCompany(). '</CompanyName>' : '';
        $xml .= '<FirstName>'.$model->getFirstname().'</FirstName>';
        $xml .= '<LastName>'.$model->getLastname().'</LastName>';
        $xml .= $this->getAddress($billAddress);
        $xml .= $this->getAddress($shipAddress, 'ship');
        $xml .= $billAddress ? '<Phone>' . $billAddress->getTelephone() . '</Phone>' : '';
        $xml .= '<Email>'.$model->getEmail().'</Email>';

        return $xml;
    }

    /**
     * Get Address XML
     *
     * @param \Magento\Customer\Model\Address $address
     * @param string $type
     * @return string
     */
    protected function getAddress($address, $type = 'bill')
    {
        if (!$address) {
            return '';
        }
        $countryName = $address->getCountryModel()
                ->loadByCode($address->getCountryId())
                ->getName();

        $xml = $type == 'bill' ? '<BillAddress>' : '<ShipAddress>';
        $xml .= '<Addr1>'.$address->getStreetLine(1).'</Addr1>';
        $xml .= '<Addr2>'.$address->getStreetLine(2).'</Addr2>';
        $xml .= '<City>'.$address->getCity().'</City>';
        $xml .= '<State>'.$address->getRegion().'</State>';
        $xml .= '<PostalCode>'.$address->getPostcode().'</PostalCode>';
        $xml .= '<Country>'.$countryName.'</Country>';
        $xml .= $type == 'bill' ? '</BillAddress>' : '</ShipAddress>';

        return $xml;
    }
}
