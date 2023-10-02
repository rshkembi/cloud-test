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

namespace Aheadworks\Blog\Model\Email\Subscriber;

use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow as EmailSubscriberDataRowResourceModel;

class DataRow extends AbstractModel implements DataRowInterface
{
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(EmailSubscriberDataRowResourceModel::class);
    }

    /**
     * Get data row entity id
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set data row entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get customer id
     *
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->hasData(self::CUSTOMER_ID) ? (int)$this->getData(self::CUSTOMER_ID): null;
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): self
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): self
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId(): int
    {
        return $this->getData(self::WEBSITE_ID);
    }

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId(int $websiteId): self
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * Get notification type
     *
     * @return string
     */
    public function getNotificationType(): string
    {
        return $this->getData(self::NOTIFICATION_TYPE);
    }

    /**
     * Set notification type
     *
     * @param string $notificationType
     * @return $this
     */
    public function setNotificationType(string $notificationType): self
    {
        return $this->setData(self::NOTIFICATION_TYPE, $notificationType);
    }

    /**
     * Get value
     *
     * @return bool
     */
    public function getValue(): bool
    {
        return (bool)$this->getData(self::VALUE);
    }

    /**
     * Set value
     *
     * @param bool $value
     * @return $this
     */
    public function setValue(bool $value): self
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface $extensionAttributes
    ):self {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
