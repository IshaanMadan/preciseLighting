<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Review\Model\Review;

class GenerateReviewFixturesCommand extends \MageWorkshop\DetailedReview\Console\AbstractFixturesCommand
{
    // use 0 to generate data for all products
    const PRODUCTS_COUNT = 50;

    const MIN_REVIEWS = 100;

    const MAX_REVIEWS = 150;

    /** @var \Magento\Review\Model\ReviewFactory $reviewFactory */
    protected $reviewFactory;

    /** @var \Magento\Review\Model\ResourceModel\Review\Summary\CollectionFactory $summaryCollectionFactory */
    protected $summaryCollectionFactory;

    /** @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory */
    protected $productCollectionFactory;

    /** @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory */
    protected $customerCollectionFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
    protected $storeManager;

    /** @var array $attributes */
    protected $attributes;

    /**
     * GenerateFixturesCommand constructor.
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Review\Model\ResourceModel\Review\Summary\CollectionFactory $summaryCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\State $appState,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review\Summary\CollectionFactory $summaryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $name = null
    ) {
        parent::__construct($eavConfig, $appState, $name);
        $this->reviewFactory = $reviewFactory;
        $this->summaryCollectionFactory = $summaryCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('mageworkshop:detailedreview:generate-review-fixtures')
            ->setDescription('MageWorkshop DetailedReview Review Fixtures generator (up to 150 reviews for 5 products with less than 20 reviews)');
        parent::configure();
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $this->generateData($output);
    }

    /**
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function generateData(
        OutputInterface $output
    ) {
        $baseUrl = $this->storeManager->getDefaultStoreView()->getBaseUrl();
        $defaultStoreId = $this->storeManager->getDefaultStoreView()->getId();

        $date = date('H:i:s');
        $output->writeln(
            sprintf(
                '<info>%s | Started analysing data and generating reviews for %d products...</info>',
                $date,
                self::PRODUCTS_COUNT
            )
        );

        /*
        // Get some random products to generate review for. Only products with some minimal number
        // of reviews are retrieved
        $summaryCollection = $this->summaryCollectionFactory->create();
        $summaryCollection->addFieldToFilter('reviews_count', ['lt' => 20])
        $summaryCollection->addFieldToFilter('entity_type', 1);
        $summaryCollection->getSelect()
            ->reset(Zend_Db_Select::COLUMNS)
            ->columns('entity_pk_value')
            ->distinct()
            ->where('store_id', 0);
        $productIds = [];

        /** @var \Magento\Review\Model\Review\Summary $summary * /
        foreach ($summaryCollection as $summary) {
            $productIds[] = $summary->getEntityPkValue();
        }
         */
        $productCollection = $this->productCollectionFactory->create();
        $productIds = $productCollection->load()->getAllIds();
        // For some reason $select->orderRand() does not really return random data
        $randomProducts = [];

        if (!self::PRODUCTS_COUNT || (self::PRODUCTS_COUNT >= count($productIds))) {
            $randomProducts = $productIds;
        } else {
            foreach (array_rand($productIds, self::PRODUCTS_COUNT) as $randomKey) {
                $randomProducts[] = $productIds[$randomKey];
            }
        }

        // Get the products to generate reviews for
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addIdFilter($randomProducts)
            ->addUrlRewrite();

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($productCollection as $product) {
            $reviewsToGenerate = mt_rand(self::MIN_REVIEWS, self::MAX_REVIEWS);
            $productId = $product->getId();
            $date = date('H:i:s');
            $output->writeln("<info>$date | Generating $reviewsToGenerate for product #$productId: "
                . "$baseUrl{$product->getRequestPath()}</info>");
            $memoryPeakUsage = memory_get_peak_usage() / (1204 * 1024);
            $output->writeln("<info>$date | Memory peak usage: $memoryPeakUsage mb</info>");

            while ($reviewsToGenerate > 0) {
                $review = $this->reviewFactory->create();
                $this->populateData($review, $this->getAttributes());
                $review->setEntityPkValue($productId)
                    ->setEntityId(1)
                    ->setStoreId($defaultStoreId)
                    ->setStores([$defaultStoreId]);
                $review->save();
                $reviewsToGenerate--;
            }
        }
    }

    /**
     * @return array
     */
    protected function getAttributes()
    {
        if (null === $this->attributes) {
            $customerCollection = $this->customerCollectionFactory->create();
            $this->attributes = [
                'nickname' => [
                    'input_type'   => 'text',
                    'has_options'  => false,
                    'options'      => []
                ],
                'title' => [
                    'input_type'   => 'text',
                    'has_options'  => false,
                    'options'      => []
                ],
                'detail' => [
                    'input_type'   => 'textarea',
                    'has_options'  => false,
                    'options'      => []
                ],
                'status_id' => [
                    'input_type'   => 'select',
                    'has_options'  => true,
                    // should have more approved reviews
                    'options'      => [
                        Review::STATUS_NOT_APPROVED,
                        Review::STATUS_APPROVED,
                        Review::STATUS_PENDING,
                        Review::STATUS_APPROVED
                    ]
                ],
                'customer_id' => [
                    'input_type'   => 'select',
                    'has_options'  => true,
                    'options'      => $customerCollection->getAllIds(),
                    'can_be_empty' => true
                ]
            ];
        }
        return $this->attributes;
    }
}
