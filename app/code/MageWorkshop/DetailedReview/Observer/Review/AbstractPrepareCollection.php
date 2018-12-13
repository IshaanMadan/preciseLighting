<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Observer\Review;

abstract class AbstractPrepareCollection implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Magento\Framework\App\RequestInterface $request */
    protected $request;

    /**
     * AbstractPrepareCollection constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param $requestParam
     * @return string
     */
    protected function getRequestParam($requestParam)
    {
        return $this->request->getParam($requestParam);
    }
}
