<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\ImageLoader\Plugin\DetailedReview\Setup;

use MageWorkshop\DetailedReview\Setup\DetailsSetup;

class DetailsSetupPlugin
{
    /**
     * @param DetailsSetup $subject
     * @param array $result
     * @return array
     */
    public function afterGetEntityAttributes(DetailsSetup $subject, array $result)
    {
        $result['image'] = [
            'input'      => 'image',
            'label'      => 'Image',
            'global'     => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
            'type'       => 'varchar',
            'group'      => 'General',
            'required'   => false,
            'sort_order' => 140,
        ];
        return $result;
    }
}
