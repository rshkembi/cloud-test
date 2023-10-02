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

namespace Aheadworks\BlogGraphQl\Model\DataProcessor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\Api\TagRepositoryInterface;
use Aheadworks\BlogGraphQl\Model\DataProcessor\DataProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

class Tags implements DataProcessorInterface
{
    /**
     * @param TagRepositoryInterface $tagRepository
     * @param DataObjectProcessor $dataObjectProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly TagRepositoryInterface $tagRepository,
        private readonly DataObjectProcessor $dataObjectProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Process data array before send
     *
     * @param array $data
     * @param array $args
     * @return array
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process(array $data, array $args): array
    {
        /** @var PostInterface $post */
        $post = $data['model'];
        $tagIds = [];
        if ($post->getTagIds()) {
            foreach ($post->getTagIds() as $item) {
                if (!in_array($item, $tagIds)) {
                    $tagIds[] = $item;
                }
            }
            $data = $this->AddTagsToPost($tagIds, $data);
        }

        return $data;
    }

    /**
     * Add tags to blog post
     *
     * @param array $tagIds
     * @param array $post
     * @return array
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function AddTagsToPost(array $tagIds, array $post): array
    {
        $this->searchCriteriaBuilder
            ->addFilter(TagInterface::ID, $tagIds, 'in')
            ->addSortOrder(
                new SortOrder(
                    [
                        SortOrder::FIELD => TagInterface::NAME,
                        SortOrder::DIRECTION => SortOrder::SORT_ASC
                    ]
                )
            );

        $tags = [];
        $tagsList = $this->tagRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($tagsList->getItems() as $tag) {
            $tags[$tag->getId()] = $this->dataObjectProcessor->buildOutputDataArray($tag, TagInterface::class);
            $tags[$tag->getId()]['url_key'] = urlencode($tag->getName());
        }

        foreach ($post['tag_ids'] as $value) {
            if (array_key_exists($value, $tags)) {
                $post['tags']['items'][] = $tags[$value];
            }
        }

        return $post;
    }
}
