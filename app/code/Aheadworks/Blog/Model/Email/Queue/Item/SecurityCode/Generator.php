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

namespace Aheadworks\Blog\Model\Email\Queue\Item\SecurityCode;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Math\Random;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\EmailQueueItemRepositoryInterface;

class Generator
{
    /**
     * Security code length
     */
    public const CODE_LENGTH = 32;

    /**
     * @param EmailQueueItemRepositoryInterface $emailQueueItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Random $random
     */
    public function __construct(
        private readonly EmailQueueItemRepositoryInterface $emailQueueItemRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly Random $random
    ) {
    }

    /**
     * Get security code
     *
     * @return string
     * @throws LocalizedException
     */
    public function getCode(): string
    {
        do {
            $securityCode = $this->random->getRandomString(self::CODE_LENGTH);

            $this->searchCriteriaBuilder->addFilter(
                EmailQueueItemInterface::SECURITY_CODE,
                $securityCode,
                'eq'
            );
            $result = $this->emailQueueItemRepository->getList(
                $this->searchCriteriaBuilder->create()
            );
        } while ($result->getTotalCount() > 0);

        return $securityCode;
    }
}
