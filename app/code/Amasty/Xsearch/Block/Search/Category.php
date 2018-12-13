<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Xsearch
 */


namespace Amasty\Xsearch\Block\Search;

class Category extends AbstractSearch
{
    const CATEGORY_BLOCK_TYPE = 'category';

    /**
     * @var array
     */
    private $categoryTitles;

    /**
     * @return string
     */
    public function getBlockType()
    {
        return self::CATEGORY_BLOCK_TYPE;
    }

    /**
     * @inheritdoc
     */
    protected function prepareCollection()
    {
        $collection = $this->getSearchCollection()
            ->addNameToResult()
            ->addAttributeToSelect('*')
            ->addUrlRewriteToResult()
            ->addIsActiveFilter()
            ->addSearchFilter($this->getQuery()->getQueryText())
            ->setPageSize($this->getLimit());
        $collection->load();
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $item
     * @return bool
     */
    public function showDescription(\Magento\Framework\Model\AbstractModel $item)
    {
        return $this->stringUtils->strlen($item->getDescription()) > 0;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $item
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItemTitle(\Magento\Framework\Model\AbstractModel $item)
    {
        $path = array_reverse(explode(',', $item->getPathInStore()));
        $categoryTitle = '';
        $titles = $this->getCategoryTitles();
        foreach ($path as $id) {
            if (!empty($titles[$id])) {
                $categoryTitle .= $titles[$id];
                $categoryTitle .= ($id !== $item->getId()) ? ' — ' : '';
            }
        }

        return $categoryTitle ?: $item->getName();
    }

    /**
     * @return array
     */
    private function getCategoryTitles()
    {
        if ($this->categoryTitles === null) {
            $this->categoryTitles = [];
            $collection = $this->getData('categoryCollectionFactory')
                ->create()
                ->addNameToResult();
            foreach ($collection as $category) {
                $this->categoryTitles[$category->getId()] = $category->getName();
            }

        }

        return $this->categoryTitles;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $category
     * @return string
     */
    public function getDescription(\Magento\Framework\Model\AbstractModel $category)
    {
        $descLength = $this->getDescLength();
        $descStripped = $this->stripTags($category->getDescription(), null, true);
        $text = $this->stringUtils->strlen($descStripped) > $descLength ?
            $this->stringUtils->substr($descStripped, 0, $this->getDescLength()) . '...'
            : $descStripped;
        return $this->highlight($text);
    }
}
