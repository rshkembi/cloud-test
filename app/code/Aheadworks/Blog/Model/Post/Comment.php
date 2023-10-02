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

namespace Aheadworks\Blog\Model\Post;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post;
use Aheadworks\Blog\Model\ResourceModel\Post\Comment as CommentResourceModel;
use Laminas\Validator\ValidatorInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Validator\AbstractValidator;

class Comment extends AbstractModel implements CommentInterface, IdentityInterface
{
    /**
     * @param Context $context
     * @param Registry $registry
     * @param AbstractValidator $validator
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private readonly AbstractValidator $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CommentResourceModel::class);
    }

    /**
     * Get comment id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set comment id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Get post id
     *
     * @return int|null
     */
    public function getPostId(): ?int
    {
        return (int)$this->getData(self::POST_ID);
    }

    /**
     * Set post id
     *
     * @param int $postId
     * @return $this
     */
    public function setPostId(int $postId): self
    {
        return $this->setData(self::POST_ID, (int)$postId);
    }

    /**
     * Get author name
     *
     * @return string|null
     */
    public function getAuthorName(): ?string
    {
        return $this->getData(self::AUTHOR_NAME);
    }

    /**
     * Set author name
     *
     * @param string $authorName
     * @return $this
     */
    public function setAuthorName(string $authorName): self
    {
        return $this->setData(self::AUTHOR_NAME, $authorName);
    }

    /**
     * Get author email
     *
     * @return string|null
     */
    public function getAuthorEmail(): ?string
    {
        return $this->getData(self::AUTHOR_EMAIL);
    }

    /**
     * Set author email
     *
     * @param string $authorEmail
     * @return $this
     */
    public function setAuthorEmail(string $authorEmail): self
    {
        return $this->setData(self::AUTHOR_EMAIL, $authorEmail);
    }

    /**
     * Get path
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->getData(self::PATH);
    }

    /**
     * Set path
     *
     * @param string|null $path
     * @return $this
     */
    public function setPath(?string $path): self
    {
        return $this->setData(self::PATH, $path);
    }

    /**
     * Get creation date
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }


    /**
     * Set creation date
     *
     * @param string|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?string $createdAt): self
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated date
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated date
     *
     * @param string|null $updatedAt
     * @return $this
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Get reply to comment id
     *
     * @return int|null
     */
    public function getReplyToCommentId()
    {
        return $this->getData(self::REPLY_TO_COMMENT_ID);
    }

    /**
     * Set reply to comment id
     *
     * @param int|null $replyToCommentId
     * @return $this
     */
    public function setReplyToCommentId(?int $replyToCommentId): self
    {
        return $this->setData(self::REPLY_TO_COMMENT_ID, $replyToCommentId);
    }

    /**
     * Get store id
     *
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return (int)$this->getData(self::STORE_ID);
    }

    /**
     * Set store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get status value
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set status value
     *
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get parent author name value
     *
     * @return string|null
     */
    public function getParentAuthorName(): ?string
    {
        return $this->getData(self::PARENT_AUTHOR_NAME);
    }

    /**
     * Set parent author name value
     *
     * @param string|null $parentAuthorName
     * @return self
     */
    public function setParentAuthorName(?string $parentAuthorName): self
    {
        return $this->setData(self::PARENT_AUTHOR_NAME, $parentAuthorName);
    }

    /**
     * Get comment children value
     *
     * @return CommentInterface[]|null
     */
    public function getChildren(): ?array
    {
        return $this->getData(self::CHILDREN);
    }

    /**
     * Set comment children value
     *
     * @param CommentInterface[] $children
     * @return $this
     */
    public function setChildren(array $children): self
    {
        return $this->setData(self::CHILDREN, $children);
    }

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\CommentExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\CommentExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Blog\Api\Data\CommentExtensionInterface $extensionAttributes
    ): self {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * Returns validator
     *
     * @return ValidatorInterface|null
     */
    protected function _getValidatorBeforeSave()
    {
        return $this->validator;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [Post::CACHE_TAG . '_' . $this->getPostId()];
    }
}
