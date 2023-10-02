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

use Aheadworks\Blog\Model\Flag;
use Aheadworks\Blog\Model\FlagFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Aheadworks\Blog\Api\EmailQueueManagementInterface;

class MailSender extends CronAbstract
{
    /**
     * Cron run interval in seconds
     */
    const RUN_INTERVAL = 300;

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
        if ($this->isLocked(Flag::AW_BLOG_SEND_EMAILS_LAST_EXEC_TIME, self::RUN_INTERVAL)) {
            return $this;
        }
        $this->emailQueueService->sendScheduledItems();

        $this->setFlagData(Flag::AW_BLOG_SEND_EMAILS_LAST_EXEC_TIME);

        return $this;
    }
}
