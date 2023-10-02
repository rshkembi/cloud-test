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

namespace Aheadworks\Blog\Model\Entity\Comment\Creation;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Model\Entity\ProcessorInterface as EntityProcessorInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Path implements EntityProcessorInterface
{
    /**
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * Set default status
     *
     * @param CommentInterface $entity
     * @return CommentInterface
     */
    public function process($entity)
    {
        if ($entity->getReplyToCommentId()) {
            try {
                $commentId = (int)$entity->getReplyToCommentId();
                $comment = $this->commentRepository->getById($commentId);
            } catch (NoSuchEntityException) {
                $comment = null;
            }
            $entity->setPath($comment?->getPath());
        }

        return $entity;
    }
}
