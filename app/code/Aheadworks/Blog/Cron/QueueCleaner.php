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

namespace Aheadworks\Blog\Cron;

use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Model\Flag;
use Aheadworks\Blog\Model\FlagFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;

class QueueCleaner extends CronAbstract
{
    /**
     * Cron run interval in seconds: at least, 1 day
     */
    const RUN_INTERVAL = 1*24*60*60-10;

    /**
     * @param DateTime $dateTime
     * @param FlagFactory $flagFactory
     * @param EmailQueueManagementInterface $emailQueueService
     */
    public function __construct(
        DateTime $dateTime,
        FlagFactory $flagFactory,
        private readonly EmailQueueManagementInterface $emailQueueService
    ) {
        parent::__construct($dateTime, $flagFactory);
    }

    /**
     * Main cron job entry point
     *
     * @return $this
     */
    public function execute()
    {
        if ($this->isLocked(Flag::AW_BLOG_CLEAR_QUEUE_LAST_EXEC_TIME, self::RUN_INTERVAL)) {
            return $this;
        }

        $this->emailQueueService->deleteProcessedItems();
        $this->setFlagData(Flag::AW_BLOG_CLEAR_QUEUE_LAST_EXEC_TIME);

        return $this;
    }
}
