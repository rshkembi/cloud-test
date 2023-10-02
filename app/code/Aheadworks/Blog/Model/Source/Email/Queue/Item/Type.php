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

class Type implements OptionSourceInterface
{
    /**#@+
     * Email queue item type values
     */
    public const ADMIN_NEW_COMMENT_FROM_VISITOR = 'aw_blog_new_comment_to_admin';
    public const ADMIN_NEW_REPLY_COMMENT_FROM_VISITOR = 'aw_blog_new_reply_comment_to_admin';
    public const SUBSCRIBER_COMMENT_PUBLISHED = 'aw_blog_comment_status_change_to_customer';
    public const SUBSCRIBER_REPLY_COMMENT = 'aw_blog_new_reply_comment_to_customer';
    public const SUBSCRIBER_COMMENT_REJECTED = 'aw_blog_subscriber_comment_rejected';
    /**#@-*/

    /**
     * @var array
     */
    private $options ;

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
                'value' => self::ADMIN_NEW_COMMENT_FROM_VISITOR,
                'label' => __('New Comment')
            ],
            [
                'value' => self::ADMIN_NEW_REPLY_COMMENT_FROM_VISITOR,
                'label' => __('New Reply Comment')
            ],
            [
                'value' => self::SUBSCRIBER_COMMENT_PUBLISHED,
                'label' => __('Comment Published')
            ],
            [
                'value' => self::SUBSCRIBER_COMMENT_REJECTED,
                'label' => __('Comment Rejected')
            ],
            [
                'value' => self::SUBSCRIBER_REPLY_COMMENT,
                'label' => __('Reply Comment')
            ],
        ];

        return $this->options;
    }
}
