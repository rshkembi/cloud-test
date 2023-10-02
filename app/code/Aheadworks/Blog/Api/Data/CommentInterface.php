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

namespace Aheadworks\Blog\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Comment interface
 * @api
 */
interface CommentInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    public const ID = 'id';
    public const POST_ID = 'post_id';
    public const AUTHOR_NAME = 'author_name';
    public const AUTHOR_EMAIL = 'author_email';
    public const PATH = 'path';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const CONTENT = 'content';
    public const REPLY_TO_COMMENT_ID = 'reply_to_comment_id';
    public const STORE_ID = 'store_id';
    public const STATUS = 'status';
    public const PARENT_AUTHOR_NAME = 'parent_author_name';
    public const CHILDREN = 'children';
    /**#@-*/

    /**
     * Get comment id
     *
     * @return int
     */
    public function getId();

    /**
     * Set comment id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get post id
     *
     * @return int|null
     */
    public function getPostId(): ?int;

    /**
     * Set post id
     *
     * @param int $postId
     * @return $this
     */
    public function setPostId(int $postId): self;

    /**
     * Get author name
     *
     * @return string|null
     */
    public function getAuthorName(): ?string;

    /**
     * Set author name
     *
     * @param string $authorName
     * @return $this
     */
    public function setAuthorName(string $authorName): self;

    /**
     * Get author email
     *
     * @return string|null
     */
    public function getAuthorEmail(): ?string;

    /**
     * Set author email
     *
     * @param string $authorEmail
     * @return $this
     */
    public function setAuthorEmail(string $authorEmail): self;

    /**
     * Get path
     *
     * @return string|null
     */
    public function getPath(): ?string;

    /**
     * Set path
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self;

    /**
     * Get creation date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set creation date
     *
     * @param string|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?string $createdAt): self;

    /**
     * Get updated date
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set updated date
     *
     * @param string|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?string $updatedAt): self;

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self;

    /**
     * Get reply to comment id
     *
     * @return int|null
     */
    public function getReplyToCommentId();

    /**
     * Set reply to comment id
     *
     * @param int|null $replyToCommentId
     * @return $this
     */
    public function setReplyToCommentId(?int $replyToCommentId): self;

    /**
     * Get store id
     *
     * @return int|null
     */
    public function getStoreId(): ?int;

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self;

    /**
     * Get status value
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Set status value
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self;

    /**
     * Get parent author name value
     *
     * @return string|null
     */
    public function getParentAuthorName(): ?string;

    /**
     * Set parent author name value
     *
     * @param string|null $parentAuthorName
     * @return self
     */
    public function setParentAuthorName(?string $parentAuthorName): self;

    /**
     * Get comment children value
     *
     * @return \Aheadworks\Blog\Api\Data\CommentInterface[]|null
     */
    public function getChildren(): ?array;

    /**
     * Set comment children value
     *
     * @param \Aheadworks\Blog\Api\Data\CommentInterface[] $children
     * @return $this
     */
    public function setChildren(array $children): self;

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\CommentExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\CommentExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Aheadworks\Blog\Api\Data\CommentExtensionInterface $extensionAttributes): self;
}

