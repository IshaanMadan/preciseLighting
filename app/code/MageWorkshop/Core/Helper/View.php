<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Core\Helper;

class View extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Sets the data to all children of the block specified
     * @param $block
     * @param string $listBlockName
     * @param array $data
     * @return string
     */
    public function getListTextChildrenHtml(\Magento\Framework\View\Element\Template $block, $listBlockName, array $data)
    {
        /** @var \Magento\Framework\View\Element\Template $listBlock */
        if (!($listBlock = $block->getChildBlock($listBlockName))) {
            $this->_logger->error('Cannot access the list block ' . $listBlockName . '.');
            return '';
        }

        foreach ($listBlock->getChildNames() as $name) {
            $childBlock = $listBlock->getChildBlock($name);

            foreach ($data as $key => $dataItem) {
                $childBlock->setDataUsingMethod($key, $dataItem);
            }
        }

        return $listBlock->toHtml();
    }
}