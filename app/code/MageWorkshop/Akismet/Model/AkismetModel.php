<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Akismet\Model;

use Magento\Framework\DataObject;

class AkismetModel
{
    const XML_PATH_MAGEWORKSHOP_AKISMET_GENERAL_ENABLED = 'mageworkshop_detailedreview/mageworkshop_akismet/enabled';
    const XML_PATH_MAGEWORKSHOP_AKISMET_GENERAL_API_KEY = 'mageworkshop_detailedreview/mageworkshop_akismet/api_key';

    /** @var \MageWorkshop\Akismet\Model\AkismetService $service */
    protected $service;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /** @var \Magento\Framework\HTTP\Header $header */
    protected $header;

    /** @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress */
    protected $remoteAddress;

    /**
     * @param \MageWorkshop\Akismet\Model\AkismetService $service
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Magento\Framework\HTTP\Header $header
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \MageWorkshop\Akismet\Model\AkismetService $service,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Framework\HTTP\Header $header,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->service = $service;
        $this->remoteAddress = $remoteAddress;
        $this->header = $header;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param array $data
     * @param string $commentType
     * @return bool
     * @throws \Exception
     */
    public function isSpam(array $data, $commentType = 'contact')
    {
        $isSpam = false;
        $apiKey = $this->scopeConfig->getValue(self::XML_PATH_MAGEWORKSHOP_AKISMET_GENERAL_API_KEY);
        if ($this->service->verifyKey($apiKey)) {
            $data = new DataObject($data);
            $data = [
                'user_ip'              => $this->remoteAddress->getRemoteAddress(),
                'user_agent'           => $this->header->getHttpUserAgent(),
                'comment_type'         => $commentType,
                'comment_author'       => $data->getName(),
                'comment_author_email' => ($data->getEmail())? $data->getEmail() : null,
                'comment_content'      => $data->getComment()
            ];
            if ($this->service->isSpam($data)) {
                $isSpam = true;
            }
        }
        return $isSpam;
    }
}