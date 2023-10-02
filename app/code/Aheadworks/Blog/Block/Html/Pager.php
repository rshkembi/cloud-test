<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Blog
 * @version    2.17.1
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Blog\Block\Html;

use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\Data\PostSearchResultsInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager as ThemePager;

/**
 * Pager block
 *
 * @method $this setPath($path)
 * @method string getRepository()
 *
 * @package Aheadworks\Blog\Block\Html
 */
class Pager extends ThemePager
{
    /**
     * @var SearchCriteria|null
     */
    private $searchCriteria = null;

    /**
     * @var PostRepositoryInterface|AuthorRepositoryInterface|null
     */
    private $repository = null;

    /**
     * @var PostSearchResultsInterface|null
     */
    private $resultItems = null;

    /**
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        if ($this->getRepository()) {
            $this->repository = $objectManager->create($this->getRepository());
        }
    }

    /**
     * Apply pagination
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return $this
     */
    public function applyPagination(
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $searchCriteriaBuilder->setCurrentPage($this->getCurrentPage());
        if ($limit = (int)$this->getLimit()) {
            $searchCriteriaBuilder->setPageSize($limit);
        }
        $pagerSearchCriteriaBuilder = clone $searchCriteriaBuilder;
        $this->searchCriteria = $pagerSearchCriteriaBuilder->create();
        $this->_setFrameInitialized(false);
        return $this;
    }

    /**
     * @return PostSearchResultsInterface|null
     */
    public function getResultItems()
    {
        if ($this->resultItems === null
            && $this->repository !== null
            && $this->searchCriteria !== null
        ) {
            $this->resultItems = $this->repository->getList($this->searchCriteria);
        }
        return $this->resultItems;
    }

    /**
     * Retrieves current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return (int)$this->getRequest()->getParam($this->getPageVarName(), 1);
    }

    /**
     * Retrieves current page with displacement
     *
     * @param int $displacement
     * @return int
     */
    public function getCurPageWithDisplacement($displacement = 0)
    {
        $currentPage = $this->getCurrentPage() + $displacement;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        if ($currentPage > $this->getLastPageNum()) {
            $currentPage = $this->getLastPageNum();
        }
        return $currentPage;
    }

    /**
     * Check if current page is a first page
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getCurrentPage() == 1;
    }

    /**
     * Check if current page is a last page
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->getCurrentPage() >= $this->getLastPageNum();
    }

    /**
     * Retrieve number of last page
     *
     * @return float|int
     */
    public function getLastPageNum()
    {
        $totalSize = (int)$this->getResultItems()->getTotalCount();
        $limit = (int)$this->getLimit();
        return $totalSize === 0 || $limit === 0 ? 1 : ceil($totalSize / $limit);
    }

    /**
     * Retrieve first page URL
     *
     * @return string
     */
    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }

    /**
     * Retrieve previous page URL
     *
     * @return string
     */
    public function getPreviousPageUrl()
    {
        return $this->getPageUrl(
            $this->getCurPageWithDisplacement(-1)
        );
    }

    /**
     * Retrieve next page URL
     *
     * @return string
     */
    public function getNextPageUrl()
    {
        return $this->getPageUrl(
            $this->getCurPageWithDisplacement(+1)
        );
    }

    /**
     * Retrieve last page URL
     *
     * @return string
     */
    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    /**
     * Retrieves page URL
     *
     * @param array $params
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        return $this->getUrl(
            null,
            [
                '_current' => true,
                '_escape' => true,
                '_use_rewrite' => true,
                '_fragment' => $this->getFragment(),
                '_query' => $params
            ]
        );
    }

    /**
     * Initialize frame data
     *
     * @return $this
     */
    protected function _initFrame()
    {
        if (!$this->isFrameInitialized()) {
            $start = 0;
            $end = 0;
            $lastPageNum = $this->getLastPageNum();
            $frameLength = $this->getFrameLength();
            $currentPage = $this->getCurrentPage();

            if ($lastPageNum <= $frameLength) {
                $start = 1;
                $end = $lastPageNum;
            } else {
                $half = ceil($frameLength / 2);
                if ($currentPage >= $half
                    && $currentPage <= $lastPageNum - $half
                ) {
                    $start = $currentPage - $half + 1;
                    $end = $start + $frameLength - 1;
                } elseif ($currentPage < $half) {
                    $start = 1;
                    $end = $frameLength;
                } elseif ($currentPage > $lastPageNum - $half) {
                    $end = $lastPageNum;
                    $start = $end - $frameLength + 1;
                }
            }

            $this->_frameStart = $start;
            $this->_frameEnd = $end;
            $this->_setFrameInitialized(true);
        }
        return $this;
    }
}
