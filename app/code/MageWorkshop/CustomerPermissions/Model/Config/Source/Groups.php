<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Model\Config\Source;

class Groups
{
    /** @var array $options */
    protected $options;

    /** @var \Magento\Customer\Api\GroupManagementInterface $groupManagement */
    protected $groupManagement;

    /** @var \Magento\Framework\Convert\DataObject $converter */
    protected $converter;

    /**
     * @param \Magento\Customer\Api\GroupManagementInterface $groupManagement
     * @param \Magento\Framework\Convert\DataObject $converter
     */
    public function __construct(
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Framework\Convert\DataObject $converter
    ) {
        $this->groupManagement = $groupManagement;
        $this->converter = $converter;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $groups = array_merge([$this->groupManagement->getNotLoggedInGroup()], $this->groupManagement->getLoggedInGroups());
            $this->options = $this->converter->toOptionArray($groups, 'id', 'code');
        }
        return $this->options;
    }
}