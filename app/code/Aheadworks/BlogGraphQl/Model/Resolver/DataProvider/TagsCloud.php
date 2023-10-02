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

use Aheadworks\Blog\Api\Data\TagCloudItemInterface;
use Aheadworks\Blog\Api\TagCloudItemRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class TagsCloud
 * @package Aheadworks\BlogGraphQl\Model\Resolver\DataProvider
 */
class TagsCloud implements DataProviderInterface
{
    /**
     * @var TagCloudItemRepositoryInterface
     */
    private $tagCloudItemRepository;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param TagCloudItemRepositoryInterface $tagCloudItemRepository
     * @param SearchResultFactory $searchResultFactory
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        TagCloudItemRepositoryInterface $tagCloudItemRepository,
        SearchResultFactory $searchResultFactory,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->tagCloudItemRepository = $tagCloudItemRepository;
        $this->searchResultFactory = $searchResultFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getListData(SearchCriteriaInterface $searchCriteria, $storeId): SearchResult
    {
        $tagsArray = [];
        $tags = $this->tagCloudItemRepository->getList($searchCriteria, $storeId);
        foreach ($tags->getItems() as $tag) {
            $tagsArray[] = $this->dataObjectProcessor->buildOutputDataArray($tag, TagCloudItemInterface::class);
        }

        return $this->searchResultFactory->create($tags->getTotalCount(), $tagsArray);
    }
}
