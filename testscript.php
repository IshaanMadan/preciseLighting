<?php
use \Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend'); 
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
$baseUrl = $storeManager->getStore()->getBaseUrl();
$productCollection = $objectManager->create('Magefan\Blog\Model\ResourceModel\Post\Collection')->setOrder('post_id', 'DESC')->getFirstItem();
$postimageUrl = $baseUrl.'pub/media/'.$productCollection->getFeaturedImg();
$postUrl = $baseUrl.'blog/post/'.$productCollection->getIdentifier();
?>
<div class="recent-blog container">
<div class="row">
<div class="col-sm-12"><img src="<?php echo $postimageUrl; ?>" alt="" /></div>
<div class="col-sm-12"><strong><?php echo $productCollection->getTitle(); ?></strong>
<p><?php 
echo $content= substr($productCollection->getContent(), 0, 250);
 ?></p>
<a href="<?php echo $postUrl; ?>"><span>READ MORE BLOG</span></a></div>
</div>
</div>
