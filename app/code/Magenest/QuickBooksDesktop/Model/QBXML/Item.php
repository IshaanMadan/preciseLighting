<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magento\Catalog\Model\Product as ProductModel;
use Magenest\QuickBooksDesktop\Model\QBXML;
use Magenest\QuickBooksDesktop\Model\Queue;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class Item extends QBXML
{
    /**
     * @var ProductModel
     */
    protected $_product;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Queue
     */
    protected $queue;

    /**
     * Item constructor.
     * @param ProductModel $product
     * @param ScopeConfigInterface $scopeConfig
     * @param Queue $queue
     */
    public function __construct(
        ProductModel $product,
        ScopeConfigInterface $scopeConfig,
        Queue $queue
    ) {
        $this->_product = $product;
        $this->_scopeConfig = $scopeConfig;
        $this->queue = $queue;
    }

   /**
    * Get XML using sync to QBD
    * @param $id
    * @return string
    */
   public function getXml($id)
   {
       $model = $this->_product->load($id);
       $qty = $model->getExtensionAttributes()->getStockItem()->getQty();
       $xml = '<Name>'.substr(str_replace('&', ' ', $model->getName()), 0, 30).'</Name>';
       $price = $model->getPrice();
       $finalPrice = $model->getFinalPrice();
       $type = $model->getTypeId();

       if ($qty > 0 || $type == 'virtual' || $type == 'giftcard' || $type == 'downloadable') {
           $xml .= '<SalesPrice>'.$finalPrice.'</SalesPrice>';
           $xml .= '<IncomeAccountRef>'
                     .'<FullName>'.$this->getAccountName().'</FullName>'
                  .'</IncomeAccountRef>'
                  .'<PurchaseCost>'.$price.'</PurchaseCost>'
                  .'<COGSAccountRef>'
                     .'<FullName>'.$this->getAccountName('cogs').'</FullName>'
                  .'</COGSAccountRef>'
                  .'<AssetAccountRef>'
                     .'<FullName>'.$this->getAccountName('asset').'</FullName>'
                   .'</AssetAccountRef>';
       } else {
           $xml .= '<SalesOrPurchase>'
                       .'<Desc>'.strip_tags($model->getData('short_description')).'</Desc>'
                       .'<Price>'.$price.'</Price>'
                       .'<AccountRef>'
                       .'<FullName>'.$this->getAccountName('expense').'</FullName>'
                       .'</AccountRef>'
                   .'</SalesOrPurchase>';
       }

       return $xml;
   }

    /**
     * @param string $type
     * @return mixed
     */
    protected function getAccountName($type = 'income')
    {
        $path = 'qbdesktop/account_setting/'.$type;

        return $this->_scopeConfig->getValue($path);
    }
}
