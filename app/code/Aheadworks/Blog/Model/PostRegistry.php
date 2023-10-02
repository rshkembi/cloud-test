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

use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Registry for \Aheadworks\Blog\Api\Data\PostInterface
 */
class PostRegistry
{
    /**
     * @var array
     */
    private $postRegistry = [];

    /**
     * Retrieve Post from registry
     *
     * @param int $postId
     * @return PostInterface
     * @throws NoSuchEntityException
     */
    public function retrieve($postId)
    {
        if (!isset($this->postRegistry[$postId])) {
            return null;
        }
        return $this->postRegistry[$postId];
    }

    /**
     * Remove instance of the Post from registry
     *
     * @param int $postId
     * @return void
     */
    public function remove($postId)
    {
        if (isset($this->postRegistry[$postId])) {
            unset($this->postRegistry[$postId]);
        }
    }

    /**
     * Replace existing Post with a new one
     *
     * @param PostInterface $post
     * @return $this
     */
    public function push(PostInterface $post)
    {
        if ($postId = $post->getId()) {
            $this->postRegistry[$postId] = $post;
        }
        return $this;
    }
}
