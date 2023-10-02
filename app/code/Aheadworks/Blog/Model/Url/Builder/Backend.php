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

namespace Aheadworks\Blog\Model\Url\Builder;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;

class Backend
{
    /**
     * @param UrlInterface $backendUrlBuilder
     * @param commentRepositoryInterface $commentRepository
     */
    public function __construct(
        private readonly UrlInterface $backendUrlBuilder,
        private readonly CommentRepositoryInterface $commentRepository,
    ) {
    }

    /**
     * Retrieve comment edit backend url
     *
     * @param int $commentId
     * @param array $additionalParams
     * @param int|null $storeId
     * @return string
     * @throws LocalizedException
     */
    public function getCommentUrl(int $commentId, array $additionalParams = [], ?int $storeId = null): string
    {
        $params = $additionalParams;
        $comment = $this->getComment($commentId);
        $params[CommentInterface::ID] = $comment->getId();
        return $this->getUrl(
            'aw_blog_admin/comment_builtin/edit',
            $storeId,
            $params
        );
    }

    /**
     * Get backend url
     *
     * @param string $routePath
     * @param int|string|null $scope
     * @param array $params
     * @return string
     */
    public function getUrl(string $routePath, $scope, array $params): string
    {
        return $this->backendUrlBuilder
            ->setScope($scope)
            ->getUrl(
                $routePath,
                $params
            );
    }

    /**
     * Retrieve comment
     *
     * @param int $commentId
     * @return CommentInterface
     * @throws NoSuchEntityException
     */
    private function getComment(int $commentId): CommentInterface
    {
        return $this->commentRepository->getById($commentId);
    }
}
