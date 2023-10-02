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

namespace Aheadworks\Blog\Api;

/**
 * Comment management service interface
 * @api
 */
interface CommentManagementInterface
{
    /**
     * Change status
     *
     * @param int $commentId
     * @param string $status
     * @return bool
     */
    public function changeStatus(int $commentId, string $status): bool;

    /**
     * Create new comment
     *
     * @param \Aheadworks\Blog\Api\Data\CommentInterface $comment
     * @return \Aheadworks\Blog\Api\Data\CommentInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createComment(\Aheadworks\Blog\Api\Data\CommentInterface $comment);

    /**
     * Update existing comment
     *
     * @param \Aheadworks\Blog\Api\Data\CommentInterface $comment
     * @return \Aheadworks\Blog\Api\Data\CommentInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateComment(\Aheadworks\Blog\Api\Data\CommentInterface $comment);

    /**
     * Delete existing comment
     *
     * @param \Aheadworks\Blog\Api\Data\CommentInterface $comment
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteComment(\Aheadworks\Blog\Api\Data\CommentInterface $comment): bool;

    /**
     * Delete existing comment by id
     *
     * @param int $commentId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteCommentById(int $commentId): bool;
}
