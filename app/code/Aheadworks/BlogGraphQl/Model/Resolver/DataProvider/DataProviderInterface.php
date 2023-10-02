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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ListDataProviderInterface
 * @package Aheadworks\BlogGraphQl\Model\Resolver\DataProvider
 */
interface DataProviderInterface
{
    /**
     * Retrieve data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $storeId
     * @return SearchResult
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId): SearchResult;
}
