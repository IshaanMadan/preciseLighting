<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Helper;

class ValidationRulesListHelper
{
    /**
     * @return array
     */
    public function getAllValidationOptions()
    {
        return [
            'maximum-length' => [
                'label' => 'Maximum length',
                'has_params' => 1,
                'params_additional_class' => 'required-entry validate-number',
                'applicable_for' => ['text', 'textarea']
            ],
            'minimum-length' => [
                'label' => 'Minimum length',
                'has_params' => 1,
                'params_additional_class' => 'required-entry validate-number',
                'applicable_for' => ['text', 'textarea']
            ],
            'validate-number' => [
                'label' => 'Validate number',
                'has_params' => 0,
                'applicable_for' => ['text']
            ],
            'validate-url' => [
                'label' => 'Validate url',
                'has_params' => 0,
                'applicable_for' => ['text', 'textarea']
            ],
            // test data
//            'url3' => [
//                'label' => 'Url3',
//                'has_params' => 0,
//                'applicable_for' => ['text', 'select', 'multiselect']
//            ],
//            'url4' => [
//                'label' => 'Url4',
//                'has_params' => 0,
//                'applicable_for' => ['text', 'swatch_visual']
//            ],
        ];
    }

    /**
     * @param string $rule
     * @return string
     */
    public function getRuleLabel($rule)
    {
        return ucfirst(str_replace('-', ' ', $rule));
    }
}
