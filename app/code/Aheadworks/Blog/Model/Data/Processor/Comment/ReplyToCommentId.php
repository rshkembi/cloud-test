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

namespace Aheadworks\Blog\Model\Data\Processor\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;

class ReplyToCommentId implements ProcessorInterface
{
    /**
     * Process data
     *
     * @param array $data
     * @return array
     */
    public function process($data): array
    {
        if (empty($data[CommentInterface::REPLY_TO_COMMENT_ID])) {
            $data[CommentInterface::REPLY_TO_COMMENT_ID] = null;
        }

        return $data;
    }
}
