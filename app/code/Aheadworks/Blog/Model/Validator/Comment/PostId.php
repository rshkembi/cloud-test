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

namespace Aheadworks\Blog\Model\Validator\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Validator\AbstractValidator;

class PostId extends AbstractValidator
{
    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param CommentInterface $model
     * @return bool
     */
    public function isValid($model): bool
    {
        $this->_clearMessages();

        $postId = $model->getPostId();
        if (!$postId) {
            $this->_addMessages(
                [
                    __('Please, specify post')
                ]
            );
        }

        return empty($this->getMessages());
    }
}
