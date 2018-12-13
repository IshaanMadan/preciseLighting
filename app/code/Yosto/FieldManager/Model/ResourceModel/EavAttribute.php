<?php
/**
 * Created by PhpStorm.
 * User: nghiata
 * Date: 09/06/2017
 * Time: 17:01
 */

namespace Yosto\FieldManager\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class EavAttribute
 * @package Yosto\FieldManager\Model\ResourceModel
 */
class EavAttribute extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('eav_attribute', 'attribute_id');
    }

}