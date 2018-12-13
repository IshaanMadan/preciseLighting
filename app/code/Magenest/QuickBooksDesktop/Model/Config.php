<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model;

use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir;

/**
 * Class Config
 * @package Magenest\QuickBooksDesktop\Model
 */
class Config
{
    const NONCE =  '0123456789ABCDEF';
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface $_cacheState
     */
    protected $_cacheState;

    /**
     * @var Filesystem\Directory\ReadFactory
     */
    protected $readFactory;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $reader;

    /**
     * @var Connector
     */
    protected $_connector;

    /**
     * Config constructor.
     * @param Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param Dir\Reader $reader
     * @param Connector $connector
     * @param User $user
     */
    public function __construct(
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\Module\Dir\Reader $reader,
        \Magenest\QuickBooksDesktop\Model\Connector $connector,
        \Magenest\QuickBooksDesktop\Model\User $user
    ) {
        $this->readFactory = $readFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_cacheState = $cacheState;
        $this->reader = $reader;
        $this->_connector = $connector;
        $this->_user = $user;
    }

    /**
     * Return generated sample.qwc configuration file
     *
     * @return string
     * @api
     */
    public function getQWCFile()
    {
        $moduleEtcPath = $this->reader->getModuleDir(Dir::MODULE_ETC_DIR, 'Magenest_QuickBooksDesktop');
        $configFilePath = $moduleEtcPath . '/qwc/sample.qwc';
        $directoryRead = $this->readFactory->create($moduleEtcPath);
        $configFilePath = $directoryRead->getRelativePath($configFilePath);
        $data = $directoryRead->readFile($configFilePath);

        return strtr($data, $this->_getReplacements());
    }
   
    /**
     * Random string with lenght
     *
     * @param int $length
     * @return string
     */
    protected function getNonce($length = 32)
    {
        $tmp = str_split(self::NONCE);
        shuffle($tmp);

        return substr(implode('', $tmp), 0, $length);
    }

    /**
     * Prepare data for qwc config
     *
     * @return array
     */
    protected function _getReplacements()
    {
        $config = $this->_connector;
        $baseUrl = $config->getBaseUrl();
        if (!empty($baseUrl)) {
            $url = $baseUrl;
        } else {
            $url = $config->getDefaultUrl();
        }
        
        $supportUrl = $url.'support.php';
        $userId = $config->getUser();
        $userName = $this->_user->load($userId)->getUsername();
        $ownerId = $this->getNonce(8).'-'.$this->getNonce(4).'-'.$this->getNonce(4).'-'.$this->getNonce(4).'-'.$this->getNonce(12);
        $fileId = $this->getNonce(8).'-'.$this->getNonce(4).'-'.$this->getNonce(4).'-'.$this->getNonce(4).'-'.$this->getNonce(12);
        $scheduler = $config->getScheduler();

        if ($config->getSync() == 1) {
            $appUrl = $url . 'qbdesktop/connection/start';
        } elseif ($config->getSync() == 2) {
            $appUrl = $url . 'qbdesktop/connection_customer/sync';
        } elseif ($config->getSync() == 4) {
            $appUrl = $url . 'qbdesktop/connection_company/sync';
        } elseif ($config->getSync() == 5) {
            $appUrl = $url . 'qbdesktop/connection_salesorder/sync';
        } elseif ($config->getSync() == 6) {
            $appUrl = $url . 'qbdesktop/connection_invoice/sync';
        } else {
            $appUrl =  $url . 'qbdesktop/connection_product/sync';
        }

        return [
                '{{AppURL}}' => $appUrl,
                '{{username}}' => $userName,
                '{{supportURL}}' => $supportUrl,
                '{{OwnerID}}' => '{' . $ownerId . '}',
                '{{FileID}}' => '{' . $fileId . '}',
                '{{minutes}}' => $scheduler,
                ];
    }
}
