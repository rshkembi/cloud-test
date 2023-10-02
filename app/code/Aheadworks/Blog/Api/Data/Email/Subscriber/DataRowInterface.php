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

namespace Aheadworks\Blog\Api\Data\Email\Subscriber;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface DataRowInterface
 */
interface DataRowInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    public const ENTITY_ID = 'entity_id';
    public const CUSTOMER_ID = 'customer_id';
    public const CUSTOMER_EMAIL = 'customer_email';
    public const WEBSITE_ID = 'website_id';
    public const NOTIFICATION_TYPE = 'notification_type';
    public const VALUE = 'value';
    /**#@-*/

    /**
     * Get data row entity id
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set data row entity id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get customer id
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId(int $customerId): self;

    /**
     * Get customer email
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Set customer email
     *
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail(string $customerEmail): self;

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId(): int;

    /**
     * Set website id
     *
     * @param int $websiteId
     * @return $this
     */
    public function setWebsiteId(int $websiteId): self;

    /**
     * Get notification type
     *
     * @return string
     */
    public function getNotificationType(): string;

    /**
     * Set notification type
     *
     * @param string $notificationType
     * @return $this
     */
    public function setNotificationType(string $notificationType): self;

    /**
     * Get value
     *
     * @return bool
     */
    public function getValue(): bool;

    /**
     * Set value
     *
     * @param bool $value
     * @return $this
     */
    public function setValue(bool $value): self;

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowExtensionInterface $extensionAttributes
    ): self;
}
