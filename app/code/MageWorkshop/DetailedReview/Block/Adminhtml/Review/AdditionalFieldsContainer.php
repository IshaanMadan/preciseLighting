<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Review;

class AdditionalFieldsContainer
    extends \Magento\Framework\View\Element\AbstractBlock
    implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $htmlId = $element->getHtmlId();
        return "<div id='$htmlId'></div>";
    }
}
