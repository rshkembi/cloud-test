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

namespace Aheadworks\Blog\Model\Email;

use Magento\Framework\DataObject;

class Metadata extends DataObject implements MetadataInterface
{
    /**
     * Get template id
     *
     * @return string
     */
    public function getTemplateId(): string
    {
        return $this->getData(self::TEMPLATE_ID);
    }

    /**
     * Set template id
     *
     * @param string $templateId
     * @return $this
     */
    public function setTemplateId(string $templateId): self
    {
        return $this->setData(self::TEMPLATE_ID, $templateId);
    }

    /**
     * Get template options
     *
     * @return array
     */
    public function getTemplateOptions(): array
    {
        return $this->getData(self::TEMPLATE_OPTIONS);
    }

    /**
     * Set template options
     *
     * @param array $templateOptions
     * @return $this
     */
    public function setTemplateOptions(array $templateOptions): self
    {
        return $this->setData(self::TEMPLATE_OPTIONS, $templateOptions);
    }

    /**
     * Get template variables
     *
     * @return array|null
     */
    public function getTemplateVariables():  ?array
    {
        return $this->getData(self::TEMPLATE_VARIABLES);
    }

    /**
     * Set template variables
     *
     * @param array $templateVariables
     * @return $this
     */
    public function setTemplateVariables(array $templateVariables): self
    {
        return $this->setData(self::TEMPLATE_VARIABLES, $templateVariables);
    }

    /**
     * Get sender name
     *
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->getData(self::SENDER_NAME);
    }

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return $this
     */
    public function setSenderName(string $senderName): self
    {
        return $this->setData(self::SENDER_NAME, $senderName);
    }

    /**
     * Get sender email
     *
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->getData(self::SENDER_EMAIL);
    }

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return $this
     */
    public function setSenderEmail(string $senderEmail): self
    {
        return $this->setData(self::SENDER_EMAIL, $senderEmail);
    }

    /**
     * Get recipient name
     *
     * @return string
     */
    public function getRecipientName(): string
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
     * @return string
     */
    public function getRecipientEmail(): string
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
}
