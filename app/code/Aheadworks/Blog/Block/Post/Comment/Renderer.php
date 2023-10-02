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

namespace Aheadworks\Blog\Block\Post\Comment;

use Aheadworks\Blog\Block\Base;

class Renderer extends Base
{
    /**
     * Retrieve array with comment data
     *
     * @return array
     */
    public function getCommentData(): array
    {
        return (array)$this->getData('comment_data');
    }

    /**
     * Set comment data array
     *
     * @param array $commentData
     * @return $this
     */
    public function setCommentData(array $commentData): self
    {
        return $this->setData('comment_data', $commentData);
    }
}
