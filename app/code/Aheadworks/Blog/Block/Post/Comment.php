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

namespace Aheadworks\Blog\Block\Post;

use Aheadworks\Blog\Block\Base;

class Comment extends Base
{
    /**
     * Is comments enabled
     *
     * @return bool
     */
    public function isCommentsEnabled(): bool
    {
        return $this->config->isCommentsEnabled();
    }

    /**
     * Get comment type
     *
     * @return string
     */
    public function getCommentType(): string
    {
        return $this->config->getCommentType();
    }
}
