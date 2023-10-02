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

namespace Aheadworks\Blog\Model\Entity\Comment\Creation;

use Aheadworks\Blog\Model\BuiltinConfig;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Entity\ProcessorInterface as EntityProcessorInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Source\Comment\Status as CommentStatus;
use Aheadworks\Blog\Model\Source\Config\Comments\Service;
use Magento\Framework\Exception\LocalizedException;

class Status implements EntityProcessorInterface
{
    /**
     * @param BuiltinConfig $builtinConfig
     * @param Config $config
     */
    public function __construct(
        private readonly BuiltinConfig $builtinConfig,
        private readonly Config $config
    ) {
    }

    /**
     * Set default status
     *
     * @param CommentInterface $entity
     * @return CommentInterface
     * @throws LocalizedException
     */
    public function process($entity)
    {
        if (!$this->config->isCommentsEnabled() || $this->config->getCommentType() === Service::DISQUS) {
            throw new LocalizedException(
                __('Can not create the comment')
            );
        }
        $entity->setStatus(
            $this->builtinConfig->isAutomaticApproving() ? CommentStatus::APPROVE : CommentStatus::PENDING
        );

        return $entity;
    }
}
