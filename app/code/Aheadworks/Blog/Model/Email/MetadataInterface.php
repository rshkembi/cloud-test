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

interface MetadataInterface
{
    /**#@+
     * public constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    public const TEMPLATE_ID = 'template_id';
    public const TEMPLATE_OPTIONS = 'template_options';
    public const TEMPLATE_VARIABLES = 'template_variables';
    public const SENDER_NAME = 'sender_name';
    public const SENDER_EMAIL = 'sender_email';
    public const RECIPIENT_NAME = 'recipient_name';
    public const RECIPIENT_EMAIL = 'recipient_email';
    /**#@-*/

    /**
     * Get template id
     *
     * @return string
     */
    public function getTemplateId();

    /**
     * Set template id
     *
     * @param string $templateId
     * @return $this
     */
    public function setTemplateId(string $templateId): self;

    /**
     * Get template options
     *
     * @return array
     */
    public function getTemplateOptions(): array;

    /**
     * Set template options
     *
     * @param array $templateOptions
     * @return $this
     */
    public function setTemplateOptions(array $templateOptions): self;

    /**
     * Get template variables
     *
     * @return array|null
     */
    public function getTemplateVariables(): ?array;

    /**
     * Set template variables
     *
     * @param array $templateVariables
     * @return $this
     */
    public function setTemplateVariables(array $templateVariables): self;

    /**
     * Get sender name
     *
     * @return string
     */
    public function getSenderName(): string;

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return $this
     */
    public function setSenderName(string $senderName): self;

    /**
     * Get sender email
     *
     * @return string
     */
    public function getSenderEmail(): string;

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return $this
     */
    public function setSenderEmail(string $senderEmail): self;

    /**
     * Get recipient name
     *
     * @return string
     */
    public function getRecipientName(): string;

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
     * @return string
     */
    public function getRecipientEmail(): string;

    /**
     * Set recipient email
     *
     * @param string $recipientEmail
     * @return $this
     */
    public function setRecipientEmail(string $recipientEmail): self;
}
