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

namespace Aheadworks\Blog\Model;

class Flag extends \Magento\Framework\Flag
{
    /**#@+
     * Constants for blog flags
     */
    public const AW_BLOG_SCHEDULE_POST_LAST_EXEC_TIME = 'aw_blog_schedule_post_last_exec_time';
    public const AW_BLOG_EXPIRED_POST_LAST_EXEC_TIME = 'aw_blog_expired_post_last_exec_time';
    public const AW_BLOG_SEND_EMAILS_LAST_EXEC_TIME = 'aw_blog_send_emails_last_exec_time';
    public const AW_BLOG_CLEAR_QUEUE_LAST_EXEC_TIME = 'aw_blog_clear_queue_last_exec_time';
    /**#@-*/

    /**
     * Setter for flag code
     * @codeCoverageIgnore
     *
     * @param string $code
     * @return $this
     */
    public function setBlogFlagCode(string $code): self
    {
        $this->_flagCode = $code;
        return $this;
    }
}
