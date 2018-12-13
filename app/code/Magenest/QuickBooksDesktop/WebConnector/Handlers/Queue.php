<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\WebConnector\Handlers;

use Magenest\QuickBooksDesktop\Model\Ticket;
use Magenest\QuickBooksDesktop\Model\Mapping;
use Magenest\QuickBooksDesktop\Model\MappingFactory;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magento\Catalog\Model\Product as ProductCollection;
use Magento\Customer\Model\Customer as CustomerModel;
use Magenest\QuickBooksDesktop\Model\Config\Source\Status;
use Magenest\QuickBooksDesktop\Helper\GenerateTicket;
use Magento\Framework\ObjectManagerInterface;
use Magenest\QuickBooksDesktop\WebConnector;
use Magenest\QuickBooksDesktop\WebConnector\Receive\Response as ReceiveResponse;

/**
 * Class Queue
 * @package Magenest\QuickBooksDesktop\WebConnector\Handlers
 */
class Queue extends WebConnector\Handlers
{
    /**
     * @var QueueFactory
     */
    protected $_queue;

    /**
     * @var MappingFactory
     */
    protected $_mapping;

    /**
     * @var ProductCollection
     */
    protected $_product;

    /**
     * @var CustomerModel
     */
    protected $customerModel;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * Queue constructor.
     * @param GenerateTicket $generateTicket
     * @param Ticket $ticket
     * @param ReceiveResponse $receiveResponse
     * @param ObjectManagerInterface $objectManager
     * @param WebConnector\Driver\Queue $driverQueue
     * @param QueueFactory $queueFactory
     * @param ProductCollection $productFactory
     * @param CustomerModel $customerModel
     * @param Mapping $map
     */
    public function __construct(
        GenerateTicket $generateTicket,
        Ticket $ticket,
        ReceiveResponse $receiveResponse,
        ObjectManagerInterface $objectManager,
        WebConnector\Driver\Queue $driverQueue,
        QueueFactory $queueFactory,
        ProductCollection $productFactory,
        CustomerModel $customerModel,
        Mapping $map,
        MappingFactory $mapping
    ) {
        parent::__construct($generateTicket, $ticket, $receiveResponse, $objectManager, $mapping);
        $this->_driver = $driverQueue;
        $this->_queue = $queueFactory;
        $this->_product = $productFactory;
        $this->customerModel = $customerModel;
        $this->_map = $map;
        $this->_mapping = $mapping;
    }

    /**
     * @param $dataFromQWC
     */
    protected function processResponse($dataFromQWC)
    {
        $response = $this->getReceiveResponse();
        $response->setResponse($dataFromQWC);
//        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug("ComeONnnn   ".print_r($response->setResponse($dataFromQWC)->parserXml->loadXML($dataFromQWC->response),true)."\n");

        $response->getAttribute();
        $statusCode = $response->getStatusCode();
        $data = [
            'ticket_id' => $response->getTicketId(),
            'dequeue_datetime' => time(),
        ];
        
        if ($statusCode == 0) {
            $data['status'] = Status::STATUS_SUCCESS;
        } else {
            $data['status'] = Status::STATUS_FAIL;
            $data['msg'] = $response->getStatusMessage();
        }

        $queue = $this->_driver->getCurrentQueue();
        $queue->addData($data);
        $queue->save();
        if ($data['status'] == Status::STATUS_SUCCESS) {
            $this->saveAttribute($response, $queue->getType());
        }

        return;
    }

    /**
     * @param $response
     * @param $type
     */
    public function saveAttribute($response, $type)
    {
        /** @var \Magenest\QuickBooksDesktop\WebConnector\Receive\Response $id */
        $id = $response->getRequestId();
        $name = $response->getName();
        $txnid = $response->getTxnId();
        $attribute = [
            'listId' => $response->getListId(),
            'editSequence' => $response->getEditSequence(),
        ];
        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug("res".print_r($attribute, true)."\n");
        //save attributes of product
        if ($type == 'Product') {
            $this->saveAttributeProduct($attribute, $id);
        //save attributes of customer
        } elseif ($type == 'Customer') {
            $this->saveAttributeCustomer($attribute, $id);
        } elseif ($type == 'ItemSalesTax') {
            $this->saveAttributeItemSalesTax($attribute, $id);
        } elseif ($type == 'SalesTaxCode') {
            $this->saveAttributeSalesTaxCode($attribute, $id);
        } elseif ($type == 'PaymentMethod') {
            $this->savePaymentMethodCode($attribute, $name);
        } elseif ($type == 'Invoice') {
            $this->saveAttributeInvoice($attribute, $id, $txnid);
        } elseif ($type == 'ShipMethod') {
            $this->saveAttributeShipMethod($attribute, $name);
        } elseif ($type == 'Vendor') {
            $this->saveVendor($attribute, $name);
        }
    }

    /**
     * @param $id
     * @return int
     */
    protected function getId($id)
    {
        $modelQueue = $this->_queue->create()->load($id);
        $setId = $modelQueue->getEntityId();
       
        return $setId;
    }

    /**
     * Save attribute of Product ( list ID and Edit sequence)
     *
     * @param $attribute
     * @param $id
     */
    public function saveAttributeProduct($attribute, $id)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'entity_id' => $this->getId($id),
            'type' => 2 , // 2 is product
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '2')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '2')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * Save attribute of Product ( list ID and Edit sequence)
     *
     * @param $attribute
     * @param $id
     */
    public function saveAttributeSalesTaxCode($attribute, $id)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'entity_id' => $this->getId($id),
            'type' => 5 , // 5 is SalesTaxCode
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '5')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '5')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * Save attribute of Product ( list ID and Edit sequence)
     *
     * @param $attribute
     * @param $id
     */
    public function saveAttributeItemSalesTax($attribute, $id)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'entity_id' => $this->getId($id),
            'type' => 6 , // 6 is ItemSalesTax
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }


    /**
     * Save attribute of Customer ( list ID and Edit sequence)
     * @param $attribute
     * @param $id
     */
    public function saveAttributeCustomer($attribute, $id)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $data = [
            'entity_id' => $this->getId($id),
            'type' => 1 , // 1 is customer
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '1')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '1')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * @param $attribute
     * @param $name
     */
    public function savePaymentMethodCode($attribute, $name)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'payment' => $name,
            'type' => 7 , // 7 is PaymentMethod
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '7')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '7')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * Save attribute of Product ( list ID and Edit sequence)
     *
     * @param $attribute
     * @param $id
     */
    public function saveAttributeInvoice($attribute, $id, $txnid)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'entity_id' => $this->getId($id),
            'type' => 3 , // 3 is Magento Order == QBD Invoice
            'company_id' => $companyId,
            'list_id' => $txnid,
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '3')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '3')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * @param $attribute
     * @param $name
     */
    public function saveAttributeShipMethod($attribute, $name)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'payment' => $name,
            'type' => 9 , // 9 is ShipMethod
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '9')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '9')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }

    /**
     * @param $attribute
     * @param $name
     */
    public function saveVendor($attribute, $name)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');

        $companyId = $company->getData('company_id');

        $data = [
            'payment' => $name,
            'type' => 10 , // 10 is Vendor
            'company_id' => $companyId,
            'list_id' => $attribute['listId'],
            'edit_sequence' => $attribute['editSequence']
        ];

        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '10')->addFieldToFilter('list_id', $attribute['listId'])->getFirstItem()->getData();

        if (!empty($check)) {
            $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '10')->addFieldToFilter('list_id', $attribute['listId'])
                ->getFirstItem()->setEditSequence($attribute['editSequence'])->save();
        } else {
            $model = $this->_mapping->create();
            $model->addData($data)->save();
        }
    }
}
