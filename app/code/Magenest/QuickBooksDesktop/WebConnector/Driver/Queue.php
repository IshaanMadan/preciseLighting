<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\WebConnector\Driver;

use Magenest\QuickBooksDesktop\WebConnector\Driver;
use Magenest\QuickBooksDesktop\Model\Config\Source\Status;

/**
 * Class Queue
 * @package Magenest\QuickBooksDesktop\WebConnector\Driver
 */
class Queue extends Driver
{
    /**
     * @return bool|int
     */
    public function getTotalsQueue()
    {
        $collection = $this->getCollection();
        $totals = $collection->getSize();
        if ($totals) {
            return $totals;
        }

        return false;
    }

    /**
     * Get Queue Collection
     *
     * @return \Magenest\QuickBooksDesktop\Model\ResourceModel\Queue\Collection
     */
    public function getCollection()
    {
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('ticket_id', ['null' => true])
                   ->addFieldToFilter('status', Status::STATUS_QUEUE);
        $collection->setOrder('priority', 'DESC');

        return $collection;
    }

    /**
     * @return \Magenest\QuickBooksDesktop\Model\Queue
     */
    public function getCurrentQueue()
    {
        $collection = $this->getCollection();

        return $collection->getFirstItem();
    }

    /**
     * @param $queue
     * @return string
     */
    public function prepareSendRequestXML($queue)
    {
        /** @var  \Magenest\QuickBooksDesktop\Model\Queue $queue */
        $action = $queue->getActionName();

        if ($queue->getType() != 'ItemOtherCharge' || $queue->getType() != 'ShipMethod') {
            $method = 'get'.$queue->getType().'Model';

            if ($queue->getType() == 'SalesTaxCode') {
                $method = 'getSalesTaxCodeModel';
            }

            if ($queue->getType() == 'ItemSalesTax') {
                $method = 'getItemSalesTaxModel';
            }

            if ($queue->getType() == 'PaymentMethod') {
                $method = 'getPaymentMethodModel';
            }

            if ($queue->getType() == 'Invoice') {
                $method = 'getSalesOrderModel';
            }

            if ($queue->getType() == 'ReceivePayment') {
                $method = 'getInvoiceModel';
            }
        /** @var QBXML $model */
            $model = $this->$method();
        }

        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <?qbxml version="13.0"?>
                <QBXML>
                    <QBXMLMsgsRq onError="continueOnError">';

//        $modelProduct = $this->_objectManager->create('\Magento\Catalog\Model\Product');

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');


        if (($action == 'CustomerMod') || ($action == 'ItemInventoryMod') || ($action == 'ItemNonInventoryMod') || ($action == 'ItemSalesTaxMod')) {
            $xml .= '<'.$action.'Rq>';
            $xml .= '<'.$action.'>';

            if (($action == 'CustomerMod')) {
                $collection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '1')->addFieldToFilter('entity_id', $queue->getData('entity_id'))->getFirstItem()->getData();
            } elseif (($action == 'ItemSalesTaxMod')) {
                $collection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('entity_id', $queue->getData('entity_id'))->getFirstItem()->getData();
            } else {
                $collection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '2')->addFieldToFilter('entity_id', $queue->getData('entity_id'))->getFirstItem()->getData();
            }
            $listId = $collection['list_id'];
            $editId = $collection['edit_sequence'];

            $xml .= '<ListID>'.$listId.'</ListID>';
            $xml .= '<EditSequence>'.$editId.'</EditSequence>';
            if (($action == 'CustomerMod')) {
                $xml .= $model->getXml($queue->getData('entity_id'));
            } elseif (($action == 'SalesTaxCodeMod')) {
                $xml .= $model->getXml($queue->getData('entity_id'));
            } elseif (($action == 'ItemSalesTaxMod')) {
                $xml .= $model->getXml($queue->getData('entity_id'));
            } else {
                $xml .= $model->getXml($queue->getData('entity_id'));
            }
            $xml .= '</'.$action.'>';
        } elseif (($action == 'ItemOtherChargeAdd')) {
            $xml .= '<'.$action.'Rq>';
            $xml .= '<'.$action.'>';
            $xml .= '<Name>'.'Shipping'.'</Name>';
            $xml .= '<IsTaxIncluded>'.'0'.'</IsTaxIncluded>';
            $xml .= '<SalesTaxCodeRef>'
                .'<FullName>'.'E'.'</FullName>'
                .'</SalesTaxCodeRef>';
            $xml .= '<SalesOrPurchase>'
                .'<AccountRef>'
                .'<FullName>'.$this->_scopeConfig->getValue('qbdesktop/account_setting/cogs').'</FullName>'
                .'</AccountRef>'
                .'</SalesOrPurchase>';
            $xml .= '</'.$action.'>';
        } elseif (($action == 'ShipMethodAdd')) {
            $xml .= '<'.$action.'Rq>';
            $xml .= '<'.$action.'>';
            $xml .= '<Name>'.$queue->getData('payment').'</Name>';
            $xml .= '</'.$action.'>';
        } elseif (($action == 'VendorAdd')) {
            $xml .= '<'.$action.'Rq>';
            $xml .= '<'.$action.'>';
            $xml .= '<Name>'.$queue->getData('payment').'</Name>';
            $xml .= '<IsSalesTaxAgency>'.'true'.'</IsSalesTaxAgency>';
            $xml .= '</'.$action.'>';
        } else {
            $xml .= '<'.$action. 'Rq requestID="'.$queue->getId().'">';
            $xml .= '<'.$action.'>';
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug("abc 1".print_r($queue->getData('entity_id'), true)."\n");

            if ($queue->getType() == 'PaymentMethod') {
                $xml .= $model->getXml($queue->getData('payment'));
            } else {
                \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug("abc 2".print_r($action, true)."\n");
                $xml .= $model->getXml($queue->getData('entity_id'));
            }
            if ($queue->getType() == 'Product') {
//                $modelProduct->load($queue->getData('entity_id'));
//                $qty = $modelProduct->getExtensionAttributes()->getStockItem()->getQty();
//                if ($qty >0) {
//                    $xml .= '<QuantityOnHand>'.$qty.'</QuantityOnHand>';
//                }
            }
            $xml .= '</'.$action.'>';
        }
        $xml .= '</'.$action.'Rq>';
        $xml .='</QBXMLMsgsRq></QBXML>';
        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug("abc".print_r($xml, true)."\n");
        return $xml;
    }

    /**
     * Customer Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Customer
     */
    protected function getCustomerModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Customer');
    }

    /**
     * @return mixed
     */
    protected function getShipMethodModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Customer');
    }

    /**
     * @return mixed
     */
    protected function getVendorModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Customer');
    }

    /**
     * @return mixed
     */
    protected function getItemOtherChargeModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Customer');
    }

    /**
     * Tax Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Tax
     */
    protected function getItemSalesTaxModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Tax\ItemSalesTax');
    }

    /**
     * Tax Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Tax
     */
    protected function getPaymentMethodModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Payment');
    }

    /**
     * Tax Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Tax
     */
    protected function getSalesTaxCodeModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Tax\SalesTaxCode');
    }

    /**
     * Product Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Customer
     */
    protected function getProductModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Item');
    }

    /**
     * Sales Order Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\SalesOrder
     */
    protected function getSalesOrderModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\SalesOrder');
    }

    /**
     * Invoice Model Object
     *
     * @return \Magenest\QuickBooksDesktop\Model\QBXML\Invoice
     */
    protected function getInvoiceModel()
    {
        return $this->_objectManager->get('Magenest\QuickBooksDesktop\Model\QBXML\Invoice');
    }

    /**
     * @param $id
     * @param $type
     * @return array
     */
    public function getCollectionAttribute($id, $type)
    {
        if ($type == 'Customer') {
            $collection = $this->_objectManager->create('\Magento\Customer\Model\Customer');
            /** @var \Magento\Customer\Model\Customer $data */
            $data = $collection->load($id);
            $info = [
                'listId' => $data->getListQbdIdCustomer(),
                'editId' => $data->getQbdEditSequenceCustomer(),
            ];
        } else {
            $collection = $this->_objectManager->create('\Magento\Catalog\Model\Product');
            /** @var \Magento\Catalog\Model\Product $data */
            $data = $collection->load($id);
            $info = [
                'listId' => $data->getListQbdIdProduct(),
                'editId' => $data->getQbdEditSequenceProduct(),
            ];
        }
        
        return $info;
    }
}
