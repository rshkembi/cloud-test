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
namespace Aheadworks\Blog\Model;

use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class DateResolver
 * @package Aheadworks\Blog\Model
 */
class DateResolver
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     */
    public function __construct(
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
    }

    /**
     * Get current datetime in DB format
     * @return string
     */
    public function getCurrentDatetimeInDbFormat()
    {
        return $this->dateTime->gmtDate();
    }

    /**
     * Get current datetime with offset in seconds
     *
     * @param int $offset
     * @param string $format
     * @return string
     */
    public function getCurrentDatetimeWithOffset($offset, $format = 'Y-m-d H:i:s')
    {
        $date = $this->dateTime->gmtTimestamp();
        $date += $offset;

        $result = date($format, $date);
        return $result;
    }
}
