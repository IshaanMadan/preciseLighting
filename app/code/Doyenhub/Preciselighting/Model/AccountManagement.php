<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Doyenhub\Preciselighting\Model;

class AccountManagement extends \Magento\Customer\Model\AccountManagement
{
   
   public function initiatePasswordReset($email, $template, $websiteId = null)
    {

        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('\Magento\Customer\Model\Session');

        $guestTocustomer=$customerSession->getGuestToCUstomer();

        if($guestTocustomer != 1){
           return parent::initiatePasswordReset($email, $template, $websiteId = null);
        }
        else{
            $customerSession->getGuestToCustomer('0');
        }
        
    }
}
