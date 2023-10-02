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

namespace Aheadworks\Blog\Model\Data\Operation\Comment;

use Aheadworks\Blog\Model\Data\OperationInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\Data\CommentInterfaceFactory;
use Aheadworks\Blog\Api\CommentManagementInterface;

class Create implements OperationInterface
{
    /**
     * @param CommentInterfaceFactory $commentFactory
     * @param CommentManagementInterface $commentService
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        private readonly CommentInterfaceFactory $commentFactory,
        private readonly CommentManagementInterface $commentService,
        private readonly DataObjectHelper $dataObjectHelper
    ) {
    }

    /**
     * Create new comment operation
     *
     * @param array $entityData
     * @return CommentInterface
     * @throws LocalizedException
     */
    public function execute(array $entityData)
    {
        /** @var CommentInterface $comment */
        $comment = $this->commentFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $comment,
            $entityData,
            CommentInterface::class
        );

        return $this->commentService->createComment($comment);
    }
}
