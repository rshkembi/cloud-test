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

namespace Aheadworks\Blog\Model\Email\Queue\DateTime;

use Aheadworks\Blog\Model\DateTime\Formatter as DateTimeFormatter;

class Resolver
{
    /**
     * @param DateTimeFormatter $dateTimeFormatter
     */
    public function __construct(
        private readonly DateTimeFormatter $dateTimeFormatter
    ) {
    }

    /**
     * Get current date and time in db format
     *
     * @return string
     */
    public function getCurrentDateTimeInDbFormat(): string
    {
        return $this->dateTimeFormatter->getCurrentDateTimeInDbFormat(null);
    }

    /**
     * Get scheduled date and time in db format
     *
     * @return string
     */
    public function getScheduledDateTimeInDbFormat(): string
    {
        return $this->getCurrentDateTimeInDbFormat();
    }

    /**
     * Retrieve deadline date for processed emails storage
     *
     * @param int $storageTimeInDays
     * @return string
     */
    public function getDeadlineDateTimeInDbFormatForProcessedEmails(int $storageTimeInDays): string
    {
        $dateToFormat = '-' . $storageTimeInDays . ' days';

        return $this->dateTimeFormatter->getDateTimeInDbFormat($dateToFormat);
    }
}
