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

namespace Aheadworks\Blog\ViewModel\Post\Comment\Listing;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Block\Base;
use Aheadworks\Blog\Block\Post\Comment\Renderer;
use Aheadworks\Blog\Block\Post\Comment\Reply\Listing;
use Aheadworks\Blog\Block\Post\Comment\Reply\Form as ReplyForm;
use Aheadworks\Blog\Model\DateTime\Formatter;
use Aheadworks\Blog\Model\StoreResolver;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CommentRenderer implements ArgumentInterface
{
    public function __construct(
        private readonly Formatter $dateFormatter,
        private readonly StoreResolver $storeResolver
    ) {
    }

    /**
     * Retrieve comment content
     *
     * @param array $commentDataRow
     * @return string
     */
    public function getContent(array $commentDataRow): string
    {
        return $commentDataRow[CommentInterface::CONTENT] ?? '';
    }

    /**
     * Retrieve comment author name
     *
     * @param array $commentDataRow
     * @return string
     */
    public function getAuthorName(array $commentDataRow): string
    {
        return $commentDataRow[CommentInterface::AUTHOR_NAME] ?? '';
    }

    /**
     * Retrieve comment id
     *
     * @param array $commentDataRow
     * @return int|null
     */
    public function getCommentId(array $commentDataRow): ?int
    {
        return $commentDataRow[CommentInterface::ID] ?? null;
    }

    /**
     * Retrieve reply to comment id
     *
     * @param array $commentDataRow
     * @return int|null
     */
    public function getReplyToCommentId(array $commentDataRow): ?int
    {
        return $commentDataRow[CommentInterface::REPLY_TO_COMMENT_ID] ?? null;
    }

    /**
     * Retrieve parent author name
     *
     * @param array $commentDataRow
     * @return string|null
     */
    public function getParentAuthorName(array $commentDataRow): ?string
    {
        return $commentDataRow[CommentInterface::PARENT_AUTHOR_NAME] ?? '';
    }

    /**
     * Retrieve comment creation date
     *
     * @param array $commentDataRow
     * @return string
     * @throws \Exception
     */
    public function getCreationDate(array $commentDataRow): string
    {
        if (!empty($commentDataRow[CommentInterface::CREATED_AT])) {
            $store = $this->storeResolver->getCurrentStore();
            $creationDate = $this->dateFormatter->formatDateToTimeAgoFormat(
                $commentDataRow[CommentInterface::CREATED_AT], $store
            );
        }

        return $creationDate ?? '';
    }

    /**
     * Retrieve comment reply list
     *
     * @param array $commentDataRow
     * @return array
     */
    public function getCommentReplyListData(array $commentDataRow): array
    {
        return $commentDataRow[CommentInterface::CHILDREN] ?? [];
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
     * Retrieve reply rendered HTML
     *
     * @param Renderer $currentBlock
     * @param string $commentReplyListingBlockAlias
     * @param array $commentReplyListData
     * @return string
     */
    public function getCommentReplyListingHtml(
        $currentBlock,
        string $commentReplyListingBlockAlias,
        array $commentReplyListData
    ): string {
        $html = '';
        $parentId = $currentBlock->getCommentData()['id'];

        /** @var Listing $commentReplyListingBlock */
        $commentReplyListingBlock = $currentBlock->getChildBlock($commentReplyListingBlockAlias);
        if ($commentReplyListingBlock) {
            $commentReplyListingBlock->setCommentReplyListData($commentReplyListData);
            $commentReplyListingBlock->setParentCommentId($parentId);
            $commentReplyListingBlock->setPageIdentifier($currentBlock->getPageIdentifier());
            $html = $currentBlock->getChildHtml($commentReplyListingBlockAlias, false);
        }

        return $html;
    }

    /**
     * Retrieve reply submit form rendered HTML
     *
     * @param Base $currentBlock
     * @param string $replyFormRendererBlockAlias
     * @param int $commentId
     * @return string
     */
    public function getReplyFormRenderedHtml($currentBlock, $replyFormRendererBlockAlias, $commentId): string
    {
        $html = '';
        /** @var ReplyForm $replyFormRendererBlock */
        $replyFormRendererBlock = $currentBlock->getChildBlock($replyFormRendererBlockAlias);
        if ($replyFormRendererBlock) {
            $replyFormRendererBlock->setCommentId($commentId);
            $html = $currentBlock->getChildHtml($replyFormRendererBlockAlias, false);
        }

        return $html;
    }
}
