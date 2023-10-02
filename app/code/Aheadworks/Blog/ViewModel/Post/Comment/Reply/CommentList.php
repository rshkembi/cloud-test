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
declare(strict_types=1);

namespace Aheadworks\Blog\ViewModel\Post\Comment\Reply;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Api\SearchCriteria\CollectionProcessor\Frontend\ConfigProvider;
use Aheadworks\Blog\Model\Post\Comment\Provider as CommentProvider;
use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver;
use Aheadworks\Blog\Model\Source\Comment\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\Blog\Block\Post\Comment\Builtin;
use Aheadworks\Blog\Block\Post\Comment\Renderer as CommentRendererBlock;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class CommentList implements ArgumentInterface
{
    public const DEFAULT_CURRENT_PAGE = 1;
    public const DEFAULT_LAST_PAGE = 1;
    public const DEFAULT_QTY = 5;

    /**
     * @param StoreManagerInterface $storeManager
     * @param ConfigProvider $configProvider
     * @param RequestInterface $request
     * @param Json $jsonSerializer
     * @param UrlInterface $urlBuilder
     * @param DataObjectProcessor $dataObjectProcessor
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param SortOrderBuilder $sortOrderBuilder
     * @param CommentProvider $commentProvider
     */
    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly ConfigProvider $configProvider,
        private readonly RequestInterface $request,
        private readonly Json $jsonSerializer,
        private readonly UrlInterface $urlBuilder,
        private readonly DataObjectProcessor $dataObjectProcessor,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly CommentProvider $commentProvider
    ) {
    }

    /**
     * Get comment reply list data
     *
     * @param $block
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCommentReplyListData($block): array
    {
        if ($block->getPageIdentifier()) {
            return $block->getCommentReplyListData();
        }

        $comments = $this->commentProvider->getChildComments($this->getData($block));

        $data = [];
        foreach ($comments as $comment) {
            $data[] = $this->dataObjectProcessor->buildOutputDataArray($comment, CommentInterface::class);
        }

        return $data;

    }

    /**
     * Get data
     *
     * @param Template $block
     * @return array
     */
    public function getData(Template $block): array
    {
        $data = [];
        $params = $this->request->getParams();
        $data[Resolver::POST_ID] = $this->getPostId($block);
        $data[Resolver::CURRENT_PAGE] = (int)$params['currentPage'];
        $data[Resolver::PARENT_COMMENT_ID] = (int)$params['parentCommentId'];
        $data[Resolver::SORT_ORDER_FIELD] = CommentInterface::CREATED_AT;
        $data[Resolver::SORT_ORDER_DIRECTION] = isset($params['direction'])
            ? $params['direction'] : SortOrder::SORT_DESC;
        $data[Resolver::CHILD_COMMENT_QTY] = self::DEFAULT_QTY;

        return $data;
    }

    /**
     * Check if need to display comment reply list
     *
     * @param array $commentReplyList
     * @return bool
     */
    public function isNeedToDisplayCommentReplyList(array $commentReplyList): bool
    {
        return (count($commentReplyList) > 0);
    }

    /**
     * Retrieve comment rendered HTML
     *
     * @param Builtin $currentBlock
     * @param string $commentReplyRendererBlockAlias
     * @param array $commentDataRow
     * @return string
     */
    public function getCommentReplyRenderedHtml(
        $currentBlock,
        string $commentReplyRendererBlockAlias,
        array $commentDataRow
    ): string {
        $html = '';

        /** @var CommentRendererBlock $commentRendererBlock */
        $commentReplyRendererBlock = $currentBlock->getChildBlock($commentReplyRendererBlockAlias);
        if ($commentReplyRendererBlock) {
            $commentReplyRendererBlock->setCommentReplyData($commentDataRow);
            $html = $currentBlock->getChildHtml($commentReplyRendererBlockAlias, false);
        }

        return $html;
    }

    /**
     * Is Show button more
     *
     * @param int|null $parentId
     * @return bool
     */
    public function isShowButtonMore(?int $parentId): bool
    {
        if (!$parentId) {
            return false;
        }

        return $this->getCurrentPage($parentId) < $this->getLastPage($parentId);
    }

    /**
     * Get current page
     *
     * @param int|null $parentId
     * @return int
     */
    public function getCurrentPage(?int $parentId): int
    {
        if (!$parentId) {
            return self::DEFAULT_CURRENT_PAGE;
        }

        return $this->configProvider->getReplyCommentCurrentPage($parentId);
    }

    /**
     * Get last page
     *
     * @param int|null $parentId
     * @return int
     */
    public function getLastPage(?int $parentId): int
    {
        if (!$parentId) {
            return self::DEFAULT_LAST_PAGE;
        }

        return $this->configProvider->getReplyCommentLastPage($parentId);
    }

    /**
     * Get config
     *
     * @param $block
     * @return string
     */
    public function getConfig($block): string
    {
        $config = [
            'url' => $this->urlBuilder->getUrl('aw_blog/post_comment/load'),
            'currentPage' => $this->getCurrentPage($this->getParentCommentId($block)),
            'parentCommentId' => $this->getParentCommentId($block),
            'direction' => $this->configProvider->getDirection(),
            'postId' => $this->getPostId($block)
        ];

        return $this->jsonSerializer->serialize($config);
    }

    /**
     * Get parent id
     *
     * @param $block
     * @return int
     */
    public function getParentCommentId($block): int
    {
        return !$block->getParentCommentId() ? (int)$this->request->getParam('parentCommentId') :$block->getParentCommentId();
    }

    /**
     * Get post id
     *
     * @param $block
     * @return int
     */
    public function getPostId($block): int
    {
        return !$block->getPageIdentifier() ? (int)$this->request->getParam('post_id') : $block->getPageIdentifier();
    }
}
