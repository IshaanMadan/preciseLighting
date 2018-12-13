<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Model\Ui;

use Magento\Framework\Data\OptionSourceInterface;
use Yosto\FieldManager\Model\EavAttribute;

/**
 * Class Mandatory
 * @package Yosto\CustomerAttribute\Model\System\Config
 */
class Mandatory implements OptionSourceInterface
{

    /**
     * @var EavAttribute
     */
    protected $_eavAttribute;

    /**
     * @param EavAttribute $eavAttribute
     */
    public function __construct(EavAttribute $eavAttribute)
    {
        $this->_eavAttribute = $eavAttribute;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->_eavAttribute->getMandatoryStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}