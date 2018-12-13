<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class User
 * @package Magenest\QuickBooksDesktop\Model
 * @method int getUserId()
 * @method string getPassword()
 * @method boolean getStatus()
 * @method string getExpiredDate()
 * @method string getRemoteIp()
 * @method string getUsername()
 * @method Use setStatus(string $status)
 */
class User extends AbstractModel
{

    protected function _construct()
    {
        $this->_init('Magenest\QuickBooksDesktop\Model\ResourceModel\User');
    }

    /**
     * Check user and password
     * @param $username
     * @param $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $pass = md5($password);
        $this->load($username, 'username');
        $time = (int)time();
        if ($this->getId()) {
            $passUser = $this->getPassword();
            $status      = $this->getStatus();
            $expiredDate = (int)strtotime($this->getExpiredDate());
            $isExpired = $expiredDate >= $time;
            if (($pass == $passUser) && ($status == '1') && ($isExpired || !$expiredDate)) {
                return true;
            }
        }

        return false;
    }
}
