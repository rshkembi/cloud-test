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

namespace Aheadworks\Blog\Model\Source\Email\Queue\Item;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**#@+
     * Email queue item status values
     */
    public const PENDING = 1;
    public const SENT = 2;
    public const CANCELED = 3;
    public const FAILED = 4;
    /**#@-*/

    /**
     * @var array
     */
    private $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = [
            [
                'value' => self::PENDING,
                'label' => __('Pending')
            ],
            [
                'value' => self::SENT,
                'label' => __('Sent')
            ],
            [
                'value' => self::CANCELED,
                'label' => __('Canceled')
            ],
            [
                'value' => self::FAILED,
                'label' => __('Failed')
            ]
        ];

        return $this->options;
    }

    /**
     * Retrieve default status
     *
     * @return int
     */
    public function getDefaultStatus(): int
    {
        return self::PENDING;
    }

    /**
     * Retrieve unprocessed statuses
     *
     * @return array
     */
    public function getUnprocessedStatuses(): array
    {
        return [self::PENDING];
    }

    /**
     * Retrieve processed statuses
     *
     * @return array
     */
    public function getProcessedStatuses(): array
    {
        return [self::SENT, self::CANCELED, self::FAILED];
    }
}
