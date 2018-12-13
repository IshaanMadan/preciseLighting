<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\Config\Source;

/**
 * Class Templates
 * @package Magenest\QuickBooksDesktop\Model\Config\Source
 */
class Templates implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $_options;

    /**
     * @var \Magenest\QuickBooksDesktop\Model\User
     */
    protected $_userCollection;

    /**
     * Templates constructor.
     * @param \Magenest\QuickBooksDesktop\Model\User $userCollection
     * @param array $data
     */
    public function __construct(
        \Magenest\QuickBooksDesktop\Model\User $userCollection,
        array $data = []
    ) {
        $this->_userCollection = $userCollection;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = [];
            /** @var \Magenest\QuickBooksDesktop\Model\User $data*/
            foreach ($this->_userCollection->getCollection() as $data) {
                $id   = $data->getUserId();
                $name = $data->getUsername();

                    $this->_options[] = ['value' => $id, 'label' => $name];
            }
        }
        
        return $this->_options;
    }
}
