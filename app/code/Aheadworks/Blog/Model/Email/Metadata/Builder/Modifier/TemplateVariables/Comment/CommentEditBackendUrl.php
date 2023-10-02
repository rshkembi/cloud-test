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

namespace Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier\TemplateVariables\Comment;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Model\Email\Metadata\Builder\ModifierInterface;
use Aheadworks\Blog\Model\Email\MetadataInterface as EmailMetadataInterface;
use Aheadworks\Blog\Model\Url\Builder\Backend as BackendUrlBuilder;
use Magento\Framework\Exception\LocalizedException;

class CommentEditBackendUrl implements ModifierInterface
{
    /**
     * @param BackendUrlBuilder $backendUrlBuilder
     */
    public function __construct(
        private readonly BackendUrlBuilder $backendUrlBuilder
    ) {
    }

    /**
     * Add metadata to existing object by connected queue item
     *
     * @param EmailMetadataInterface $emailMetadata
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailMetadataInterface
     * @throws LocalizedException
     */
    public function addMetadata(
        EmailMetadataInterface $emailMetadata,
        EmailQueueItemInterface $emailQueueItem
    ): EmailMetadataInterface {
        $templateVariables = $emailMetadata->getTemplateVariables();
        $templateVariables['comment_edit_backend_url'] =
            $this->backendUrlBuilder->getCommentUrl(
                $emailQueueItem->getObjectId(),
                [],
                $emailQueueItem->getStoreId()
            )
        ;
        $emailMetadata->setTemplateVariables($templateVariables);

        return $emailMetadata;
    }
}
