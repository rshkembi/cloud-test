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
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model\Resolver\DataProvider;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\BlogGraphQl\Model\ObjectConverter;
use Magento\Customer\Model\Context;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Posts extends AbstractDataProvider
{
    /**
     * @param PostRepositoryInterface $postRepository
     * @param SearchResultFactory $searchResultFactory
     * @param HttpContext $httpContext
     * @param ObjectConverter $objectConverter
     */
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly SearchResultFactory $searchResultFactory,
        private readonly HttpContext $httpContext,
        ObjectConverter $objectConverter,
    ) {
        parent::__construct($objectConverter);
    }

    /**
     * Retrieve data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $storeId
     * @return SearchResult
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId): SearchResult
    {
        $posts = $this->postRepository->getList($searchCriteria);
        $args['store_id'] = (int)$storeId;
        $args['customer_group'] = $this->httpContext->getValue(Context::CONTEXT_GROUP);
        $this->convertResultItemsToDataArray($posts, PostInterface::class, $args);

        return $this->searchResultFactory->create($posts->getTotalCount(), $posts->getItems());
    }
}
