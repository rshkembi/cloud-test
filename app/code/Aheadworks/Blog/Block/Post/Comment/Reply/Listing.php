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

namespace Aheadworks\Blog\Block\Post\Comment\Reply;

use Aheadworks\Blog\Block\Base;

/**
 * Builtin integration block
 *
 * @method int|null getPageIdentifier()
 * @method string|null getPageUrl()
 * @method string|null getPageTitle()
 *
 * @method $this setPageIdentifier(int)
 * @method $this setPageUrl(string)
 * @method $this setPageTitle(string)
 *
 */
class Listing extends Base
{
    /**
     * Retrieve array with comment reply data
     *
     * @return array
     */
    public function getCommentReplyListData(): array
    {
        return (array)$this->getData('comment_reply_list_data');
    }

    /**
     * Set comment reply data array
     *
     * @param array $commentReplyListData
     * @return $this
     */
    public function setCommentReplyListData(array $commentReplyListData): self
    {
        return $this->setData('comment_reply_list_data', $commentReplyListData);
    }

    /**
     * Retrieve parent comment id
     *
     * @return int|null
     */
    public function getParentCommentId(): ?int
    {
        return $this->getData('parent_comment_id');
    }

    /**
     * Set parent comment id
     *
     * @param int $commentId
     * @return $this
     */
    public function setParentCommentId(int $commentId): self
    {
        return $this->setData('parent_comment_id', $commentId);
    }
}
