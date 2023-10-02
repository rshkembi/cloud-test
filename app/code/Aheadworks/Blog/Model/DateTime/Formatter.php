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

namespace Aheadworks\Blog\Model\DateTime;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;
use Magento\Store\Api\Data\StoreInterface;

class Formatter
{
    /**
     * @param TimezoneInterface $localeDate
     * @param DateTime $dateTime
     */
    public function __construct(
        private readonly TimezoneInterface $localeDate,
        private readonly DateTime $dateTime
    ) {
    }

    /**
     * Retrieve formatted date and time, localized according to the specific store
     *
     * @param string|null $date
     * @param int|null $storeId
     * @param string $format
     * @return string
     */
    public function getLocalizedDateTime($date = null, $storeId = null, $format = StdlibDateTime::DATETIME_PHP_FORMAT)
    {
        $scopeDate = $this->localeDate->scopeDate($storeId, $date, true);
        return $scopeDate->format($format);
    }

    /**
     * Retrieve formatted date
     *
     * @param string $date
     * @param int $hour
     * @param int $minute
     * @param int $second
     */
    public function getDate($date, $hour = 0, $minute = 0, $second = 0)
    {
        $dateTime = new \DateTime($date);
        $dateTime->setTime($hour, $minute, $second);

        return $dateTime->format(StdlibDateTime::DATETIME_PHP_FORMAT);
    }

    /**
     * Get format date to time ago
     *
     * @param string $date
     * @param StoreInterface $store
     * @return string
     * @throws \Exception
     */
    public function formatDateToTimeAgoFormat(string $date, StoreInterface $store): string
    {
        $timeZone = $this->localeDate->getConfigTimezone(null, $store);

        $now = new \DateTime('now', new \DateTimeZone($timeZone ?? 'UTC'));
        $createdDate = new \DateTime($date);
        $diff = $now->diff($createdDate);
        if ($diff->y > 0) {
            $formattedDate = $createdDate->format('F j, Y');
        } elseif ($diff->m > 0) {
            $formattedDate = $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        } elseif ($diff->d > 0) {
            $formattedDate = $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        } elseif ($diff->h > 0) {
            $formattedDate = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            $formattedDate = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        } else {
            $formattedDate = 'just now';
        }

        return $formattedDate;
    }

    /**
     * Retrieve current date and time in db format
     *
     * @return string
     */
    public function getCurrentDateTimeInDbFormat(): string
    {
        return $this->getDateTimeInDbFormat(null);
    }

    /**
     * Get date and time in db format
     *
     * @param string|null $date
     * @return string
     */
    public function getDateTimeInDbFormat(?string $date = null): string
    {
        if (empty($date)) {
            $formattedDate = $this->dateTime->gmtDate(
                StdlibDateTime::DATETIME_PHP_FORMAT
            );
        } else {
            $createdAtTimestamp = strtotime($date);
            $formattedDate = $this->dateTime->gmtDate(
                StdlibDateTime::DATETIME_PHP_FORMAT,
                $createdAtTimestamp
            );
        }

        return $formattedDate;
    }
}
