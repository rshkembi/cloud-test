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

use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;

class Sender
{
    /**
     * @param TransportBuilder $transportBuilder
     */
    public function __construct(
        private readonly TransportBuilder $transportBuilder
    ) {
    }

    /**
     * Send email message according to its metadata
     *
     * @param MetadataInterface $emailMetadata
     * @return bool
     * @throws MailException
     * @throws LocalizedException
     */
    public function send(MetadataInterface $emailMetadata): bool
    {
        $this->transportBuilder
            ->setTemplateIdentifier($emailMetadata->getTemplateId())
            ->setTemplateOptions($emailMetadata->getTemplateOptions())
            ->setTemplateVars($emailMetadata->getTemplateVariables())
            ->setFromByScope(
                [
                    'name' => $emailMetadata->getSenderName(),
                    'email' => $emailMetadata->getSenderEmail()
                ]
            )->addTo(
                $emailMetadata->getRecipientEmail(),
                $emailMetadata->getRecipientName()
            );

        $this->transportBuilder->getTransport()->sendMessage();

        return true;
    }
}
