<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Xsearch
 */


namespace Amasty\Xsearch\Plugin\Framework\View\Page;

use \Magento\Framework\View\Page\Config as NativeConfig;

class Config
{
    const NO_INDEX_NO_FOLLOW = 'NOINDEX,NOFOLLOW';

    /**
     * @var \Amasty\Xsearch\Helper\Data
     */
    private $config;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * Config constructor.
     * @param \Amasty\Xsearch\Helper\Data $config
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Amasty\Xsearch\Helper\Data $config,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @param NativeConfig $subject
     * @param $result
     * @return string
     */
    public function afterGetRobots(
        NativeConfig $subject,
        $result
    ) {
        if ($this->config->isNoIndexNoFollowEnabled()
            && $this->request->getModuleName() === 'catalogsearch'
        ) {
            $result = self::NO_INDEX_NO_FOLLOW;
        }

        return $result;
    }
}
