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

namespace Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier\TemplateId;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Email\Metadata\Builder\ModifierInterface;
use Aheadworks\Blog\Model\Email\MetadataInterface as EmailMetadataInterface;

class AdminNewReplyCommentFromVisitor implements ModifierInterface
{
    /**
     * @param Config $config
     */
    public function __construct(
        private readonly Config $config
    ) {
    }

    /**
     * Add metadata to existing object by connected queue item
     *
     * @param EmailMetadataInterface $emailMetadata
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailMetadataInterface
     */
    public function addMetadata(
        EmailMetadataInterface $emailMetadata,
        EmailQueueItemInterface $emailQueueItem
    ): EmailMetadataInterface {
        $emailMetadata->setTemplateId(
            $this->config->getNotificationTemplateForAdminAboutNewReplyCommentFromVisitor(
                (int)$emailQueueItem->getStoreId()
            )
        );

        return $emailMetadata;
    }
}
