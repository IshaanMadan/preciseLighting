<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Yosto\FieldManager\Model\ResourceModel\EavAttribute\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Yosto\FieldManager\Model\EavAttributeFactory;
use \Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
/**
 * Class AbstractController
 * @package Yosto\FieldManager\Controller\Adminhtml
 */
abstract class AbstractController extends Action
{

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    protected $_entityTypeId;

    protected $_filter;

    protected $_eavAttributeFactory;

    protected $_coreRegistry;

    protected $_attributeFactory;

    protected $_cacheManager;

    protected $_cache;
    /**
     * @param Action\Context $context
     * @param LoggerInterface $logger
     * @param PageFactory $pageFactory
     * @param CollectionFactory $collectionFactory
     * @param EavAttributeFactory $eavAttributeFactory
     * @param Filter $filter
     * @param Registry $coreRegistry
     */
    public function __construct(
        Action\Context $context,
        LoggerInterface $logger,
        PageFactory $pageFactory,
        CollectionFactory $collectionFactory,
        EavAttributeFactory $eavAttributeFactory,
        Filter $filter,
        Registry $coreRegistry,
        AttributeFactory $attributeFactory,
        CacheManager $cacheManger,
        CacheTypeListInterface $cache
    ) {
        parent::__construct($context);
        $this->_logger = $logger;
        $this->_resultPageFactory = $pageFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_filter = $filter;
        $this->_eavAttributeFactory = $eavAttributeFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_attributeFactory = $attributeFactory;
        $this->_cacheManager = $cacheManger;
        $this->_cache = $cache;
    }


    /**
     * Returns result of authorisation permission
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('Yosto_Opc::field_manager');
    }


    /**
     * @param \Magento\Framework\Phrase|null $title
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function createActionPage($title = null)
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();

            $resultPage->addBreadcrumb(__('Field Manager'), __('Field Manager'))
                ->addBreadcrumb(__('Edit Attribute '), __('Edit Attribute'))
                ->setActiveMenu('Yosto_FieldManager::address_attributes');
            if (!empty($title)) {
                $resultPage->addBreadcrumb($title, $title);
            }

        $resultPage->getConfig()->getTitle()->prepend(__('Address Attributes'));
        return $resultPage;
    }
}