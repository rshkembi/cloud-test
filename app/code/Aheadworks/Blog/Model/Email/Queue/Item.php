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

namespace Aheadworks\Blog\Model\Email\Queue;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item as ItemResourceModel;
use Magento\Framework\Model\AbstractModel;

class Item extends AbstractModel implements ItemInterface
{
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ItemResourceModel::class);
    }

    /**
     * Get queue item id
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set queue item id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId): self
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get store id
     *
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return $this->hasData(self::STORE_ID) ? (int)$this->getData(self::STORE_ID) : null;
    }

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get email type
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Set email type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get status value
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status value
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get queue item creation date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set queue item creation date
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt(string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get queue item scheduled sending date
     *
     * @return string|null
     */
    public function getScheduledAt(): ?string
    {
        return $this->getData(self::SCHEDULED_AT);
    }

    /**
     * Set queue item scheduled sending date
     *
     * @param string $scheduledAt
     * @return $this
     */
    public function setScheduledAt(string $scheduledAt): self
    {
        return $this->setData(self::SCHEDULED_AT, $scheduledAt);
    }

    /**
     * Get queue item sending date
     *
     * @return string|null
     */
    public function getSentAt(): ?string
    {
        return $this->getData(self::SENT_AT);
    }

    /**
     * Set queue item sending date
     *
     * @param string $sentAt
     * @return $this
     */
    public function setSentAt(string $sentAt): self
    {
        return $this->setData(self::SENT_AT, $sentAt);
    }

    /**
     * Get recipient name
     *
     * @return string|null
     */
    public function getRecipientName(): ?string
    {
        return $this->getData(self::RECIPIENT_NAME);
    }

    /**
     * Set recipient name
     *
     * @param string $recipientName
     * @return $this
     */
    public function setRecipientName(string $recipientName): self
    {
        return $this->setData(self::RECIPIENT_NAME, $recipientName);
    }

    /**
     * Get recipient email
     *
     * @return string|null
     */
    public function getRecipientEmail(): ?string
    {
        return $this->getData(self::RECIPIENT_EMAIL);
    }

    /**
     * Set recipient email
     *
     * @param string $recipientEmail
     * @return $this
     */
    public function setRecipientEmail(string $recipientEmail): self
    {
        return $this->setData(self::RECIPIENT_EMAIL, $recipientEmail);
    }

    /**
     * Get related object id
     *
     * @return int|null
     */
    public function getObjectId(): ?int
    {
        return $this->hasData(self::OBJECT_ID) ? (int)$this->getData(self::OBJECT_ID) : null;
    }

    /**
     * Set related object id
     *
     * @param int $objectId
     * @return $this
     */
    public function setObjectId(int $objectId): self
    {
        return $this->setData(self::OBJECT_ID, $objectId);
    }

    /**
     * Get security code
     *
     * @return string|null
     */
    public function getSecurityCode(): ?string
    {
        return $this->getData(self::SECURITY_CODE);
    }

    /**
     * Set security code
     *
     * @param string $securityCode
     * @return $this
     */
    public function setSecurityCode(string $securityCode): self
    {
        return $this->setData(self::SECURITY_CODE, $securityCode);
    }

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Blog\Api\Data\Email\Queue\ItemExtensionInterface $extensionAttributes
    ): self {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
