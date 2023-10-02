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

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post\Comment\Provider as CommentProvider;
use Aheadworks\Blog\Model\Post\CommentRepository;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Reflection\DataObjectProcessor;

class Comments implements DataProviderInterface
{
    /**
     * @param CommentRepository $commentRepository
     * @param SearchResultFactory $searchResultFactory
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly SearchResultFactory $searchResultFactory,
        private readonly DataObjectProcessor $dataObjectProcessor,
    ) {
    }

    /**
     * Retrieve data
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int|null $storeId
     * @return SearchResult
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId): SearchResult
    {
        $commentsArray = [];
        $comments = $this->commentRepository->getList($searchCriteria);
        foreach ($comments->getItems() as $comment) {
            $commentsArray[] = $this->dataObjectProcessor->buildOutputDataArray($comment, CommentInterface::class);
        }

        return $this->searchResultFactory->create($comments->getTotalCount(), $commentsArray);
    }
}
