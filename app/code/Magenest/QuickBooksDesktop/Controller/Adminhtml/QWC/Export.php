<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Controller\Adminhtml\QWC;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Export
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\QWC
 */
class Export extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magenest\QuickBooksDesktop\Model\Config
     */
    protected $config;
    

    /**
     * Export constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magenest\QuickBooksDesktop\Model\Config $config
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magenest\QuickBooksDesktop\Model\Config $config
    ) {
        parent::__construct($context);
        $this->config = $config;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Export Varnish Configuration as .qwc
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $fileName = 'connect.qwc';
        $content = $this->config->getQWCFile();

        return $this->fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }

    /**
     * Always true
     *
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }
}
