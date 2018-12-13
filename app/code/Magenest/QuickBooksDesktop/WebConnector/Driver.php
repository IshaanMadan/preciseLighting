<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\WebConnector;

use Magenest\QuickBooksDesktop\Model\ResourceModel\Queue\CollectionFactory;
use Magento\Framework\ObjectManagerInterface;
use Magenest\QuickBooksDesktop\Model\Mapping as Mapping;
use Magenest\QuickBooksDesktop\Model\User as UserModel;
use Psr\Log\LoggerInterface;

/**
 * Class Driver
 * @package Magenest\QuickBooksDesktop\WebConnector
 */
abstract class Driver
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var UserModel
     */
    protected $_user;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

     /**
      * Driver constructor.
      * @param CollectionFactory $collectionFactory
      * @param ObjectManagerInterface $objectManager
      * @param UserModel $user
      * @param Mapping $map
      */
    public function __construct(
        CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager,
        LoggerInterface $loggerInterface,
        UserModel $user,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Mapping $map
    ) {
        $this->_logger            = $loggerInterface;
        $this->_user              = $user;
        $this->_map               = $map;
        $this->_collectionFactory = $collectionFactory;
        $this->_objectManager     = $objectManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Authenticate Username and password
     *
     * @param \stdClass $obj
     * @return bool
     */
    public function authenticate($obj)
    {
        $username = $obj->strUserName;
        $password = $obj->strPassword;
        $pass = md5($password);
        $model = $this->_user->load($username, 'username');
        $time = (int)time();

        if ($model->getId()) {
            $passUser = $model->getPassword();
            $status      = $model->getStatus();
            $expiredDate = (int)strtotime($model->getExpiredDate());
            $isExpired = $expiredDate >= $time;
            if (($pass == $passUser) && ($status == 1) && ($isExpired || !$expiredDate)) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * @return bool|int
     */
    public function getTotalsQueue()
    {
        //TODO in Children
    }

    /**
     * @return \Magenest\QuickBooksDesktop\Model\Queue
     */
    public function getCurrentQueue()
    {
        //TODO in Children
    }
    
    /**
     * @param $queue
     * @return string
     */
    public function prepareSendRequestXML($queue)
    {
        //TODO in Children
    }
}
