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

namespace Aheadworks\Blog\Api\Data\Email\Queue;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ItemInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';
    public const STORE_ID = 'store_id';
    public const TYPE = 'type';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const SCHEDULED_AT = 'scheduled_at';
    public const SENT_AT = 'sent_at';
    public const RECIPIENT_NAME = 'recipient_name';
    public const RECIPIENT_EMAIL = 'recipient_email';
    public const OBJECT_ID = 'object_id';
    public const SECURITY_CODE = 'security_code';
    /**#@-*/

    /**
     * Get queue item id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set queue item id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId): self;

    /**
     * Get store id
     *
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self;

    /**
     * Get email type
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Set email type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self;

    /**
     * Get status value
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set status value
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self;

    /**
     * Get queue item creation date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set queue item creation date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self;

    /**
     * Get queue item scheduled sending date
     *
     * @return string|null
     */
    public function getScheduledAt(): ?string;

    /**
     * Set queue item scheduled sending date
     *
     * @param string $scheduledAt
     * @return $this
     */
    public function setScheduledAt(string $scheduledAt): self;

    /**
     * Get queue item sending date
     *
     * @return string|null
     */
    public function getSentAt(): ?string;

    /**
     * Set queue item sending date
     *
     * @param string $sentAt
     * @return $this
     */
    public function setSentAt(string $sentAt): self;

    /**
     * Get recipient name
     *
     * @return string|null
     */
    public function getRecipientName(): ?string;

    /**
     * Set recipient name
     *
     * @param string $recipientName
     * @return $this
     */
    public function setRecipientName(string $recipientName): self;

    /**
     * Get recipient email
     *
     * @return string|null
     */
    public function getRecipientEmail(): ?string;

    /**
     * Set recipient email
     *
     * @param string $recipientEmail
     * @return $this
     */
    public function setRecipientEmail(string $recipientEmail): self;

    /**
     * Get related object id
     *
     * @return int|null
     */
    public function getObjectId(): ?int;

    /**
     * Set related object id
     *
     * @param int $objectId
     * @return $this
     */
    public function setObjectId(int $objectId): self;

    /**
     * Get security code
     *
     * @return string|null
     */
    public function getSecurityCode(): ?string;

    /**
     * Set security code
     *
     * @param string $securityCode
     * @return $this
     */
    public function setSecurityCode(string $securityCode): self;

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface $extensionAttributes
    ): self;
}
