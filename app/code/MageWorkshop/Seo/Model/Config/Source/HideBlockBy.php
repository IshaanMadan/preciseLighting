<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Seo\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class HideBlockBy implements ArrayInterface
{
    const HIDE_BY_CSS = 'css';

    const HIDE_BY_JS  = 'js';

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::HIDE_BY_CSS,
                'label' => __('CSS')
            ], [
                'value' => self::HIDE_BY_JS,
                'label' => __('JavaScript (hide SEO block after the page load)')
            ]
        ];
    }
}
