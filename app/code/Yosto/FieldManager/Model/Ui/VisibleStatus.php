<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Model\Ui;

use Magento\Framework\Data\OptionSourceInterface;
use Yosto\FieldManager\Model\EavAttribute;

/**
 * Class ColumnStatus
 * @package Yosto\FieldManager\Model\System\Config
 */
class VisibleStatus implements OptionSourceInterface
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
        $columnStatusOptions = $this->_eavAttribute->getVisibleStatuses();
        foreach ($columnStatusOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}