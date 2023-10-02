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

use Aheadworks\Blog\Model\Email\Queue\Item\Provider as EmailQueueItemProvider;

class Checker
{
    /**
     * @param EmailQueueItemProvider $emailQueueItemProvider
     */
    public function __construct(
        private readonly EmailQueueItemProvider $emailQueueItemProvider
    ) {
    }

    /**
     * Check if security code is valid
     *
     * @param string $securityCode
     * @return bool
     */
    public function isValid(string $securityCode): bool
    {
        $emailQueueItem = $this->emailQueueItemProvider->getItemBySecurityCode($securityCode);

        return !empty($emailQueueItem);
    }
}
