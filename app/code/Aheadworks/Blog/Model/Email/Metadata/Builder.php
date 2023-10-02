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

namespace Aheadworks\Blog\Model\Email\Metadata;

use Aheadworks\Blog\Model\Email\MetadataInterface as EmailMetadataInterface;
use Aheadworks\Blog\Model\Email\MetadataInterfaceFactory as EmailMetadataInterfaceFactory;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier\Pool as EmailMetadataModifierPool;

class Builder
{
    /**
     * @param EmailMetadataInterfaceFactory $emailMetadataFactory
     * @param EmailMetadataModifierPool $emailMetadataModifierPool
     */
    public function __construct(
        private readonly EmailMetadataInterfaceFactory $emailMetadataFactory,
        private readonly EmailMetadataModifierPool $emailMetadataModifierPool
    ) {
    }

    /**
     * Build email metadata by queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailMetadataInterface
     * @throws LocalizedException
     */
    public function buildForEmailQueueItem(EmailQueueItemInterface $emailQueueItem): EmailMetadataInterface
    {
        /** @var EmailMetadataInterface $emailMetadata */
        $emailMetadata = $this->emailMetadataFactory->create();
        $emailMetadataModifier = $this->emailMetadataModifierPool->getModifierByEmailQueueItemType(
            $emailQueueItem->getType()
        );
        $emailMetadata = $emailMetadataModifier->addMetadata($emailMetadata, $emailQueueItem);

        return $emailMetadata;
    }
}
