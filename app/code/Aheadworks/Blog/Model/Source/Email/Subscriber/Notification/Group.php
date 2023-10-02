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

namespace Aheadworks\Blog\Model\Source\Email\Subscriber\Notification;

use Magento\Framework\Data\OptionSourceInterface;

class Group implements OptionSourceInterface
{
    /**#@+
     * Subscriber notification group type values
     */
    public const COMMENT_UPDATE_NOTIFICATION_TYPE = '1';
    public const REPLY_UPDATE_NOTIFICATION_TYPE = '2';
    /**#@-*/

    /**
     * @var array
     */
    private $options ;

    /**
     * Return array of options as value-label pairs
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = [
            [
                'value' => self::COMMENT_UPDATE_NOTIFICATION_TYPE,
                'label' => __('Comment update notifications')
            ],
            [
                'value' => self::REPLY_UPDATE_NOTIFICATION_TYPE,
                'label' => __('Reply update notifications')
            ]
        ];

        return $this->options;
    }
}
