<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Controller\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

/**
 * Class AccessDeniedException
 * @package MageWorkshop\Voting\Controller\Exception
 */
class AccessDeniedException extends LocalizedException
{
    /**
     * AccessDeniedException constructor.
     * @param Phrase|string $phrase
     * @param \Exception|null $cause
     */
    public function __construct($phrase, \Exception $cause = null)
    {
        if (is_string($phrase)) {
            $phrase = new Phrase($phrase);
        }
        parent::__construct($phrase, $cause);
    }
}