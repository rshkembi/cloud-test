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
namespace Aheadworks\Blog\Model\Data;

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Post data model
 * @codeCoverageIgnore
 */
class Post extends AbstractExtensibleObject implements PostInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlKey()
    {
        return $this->_get(self::URL_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getShortContent()
    {
        return $this->_get(self::SHORT_CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setShortContent($shortContent)
    {
        return $this->setData(self::SHORT_CONTENT, $shortContent);
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return $this->_get(self::CONTENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor()
    {
        return $this->_get(self::AUTHOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor($author)
    {
        return $this->setData(self::AUTHOR, $author);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorId()
    {
        return $this->_get(self::AUTHOR_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorId($authorId)
    {
        return $this->setData(self::AUTHOR_ID, $authorId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getPublishDate()
    {
        return $this->_get(self::PUBLISH_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPublishDate($publishDate)
    {
        return $this->setData(self::PUBLISH_DATE, $publishDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getEndDate(): ?string
    {
        return $this->_get(self::END_DATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setEndDate($endDate): PostInterface
    {
        return $this->setData(self::END_DATE, $endDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsAllowComments()
    {
        return (bool)$this->_get(self::IS_ALLOW_COMMENTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsAllowComments($isAllowComments)
    {
        return $this->setData(self::IS_ALLOW_COMMENTS, $isAllowComments);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsFeatured()
    {
        return (bool)$this->_get(self::IS_FEATURED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsFeatured($isFeatured)
    {
        return $this->setData(self::IS_FEATURED, $isFeatured);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorDisplayMode()
    {
        return $this->_get(self::AUTHOR_DISPLAY_MODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthorDisplayMode($authorDisplayMode)
    {
        return $this->setData(self::AUTHOR_DISPLAY_MODE, $authorDisplayMode);
    }

    /**
     * {@inheritdoc}
     */
    public function getAreRelatedProductsEnabled()
    {
        return $this->_get(self::ARE_RELATED_PRODUCTS_ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setAreRelatedProductsEnabled($flag)
    {
        return $this->setData(self::ARE_RELATED_PRODUCTS_ENABLED, $flag);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsAuthorBadgeEnabled()
    {
        return $this->_get(self::IS_AUTHOR_BADGE_ENABLED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsAuthorBadgeEnabled($flag)
    {
        return $this->setData(self::IS_AUTHOR_BADGE_ENABLED, $flag);
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreIds()
    {
        return $this->_get(self::STORE_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreIds($storeIds)
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryIds()
    {
        return $this->_get(self::CATEGORY_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategoryIds($categoryIds)
    {
        return $this->setData(self::CATEGORY_IDS, $categoryIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getTagIds()
    {
        return $this->_get(self::TAG_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setTagIds($tagIds)
    {
        return $this->setData(self::TAG_IDS, $tagIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalCategoryId()
    {
        return $this->_get(self::CANONICAL_CATEGORY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCanonicalCategoryId($canonicalCategoryId)
    {
        return $this->setData(self::CANONICAL_CATEGORY_ID, $canonicalCategoryId);
    }

    /**
     * {@inheritdoc}
     */
    public function getTagNames()
    {
        return $this->_get(self::TAG_NAMES);
    }

    /**
     * {@inheritdoc}
     */
    public function setTagNames($tagNames)
    {
        return $this->setData(self::TAG_NAMES, $tagNames);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaTitle()
    {
        return $this->_get(self::META_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaTitle($metaTitle)
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaKeywords()
    {
        return $this->_get(self::META_KEYWORDS);
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaKeywords($metaKeywords)
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaDescription()
    {
        return $this->_get(self::META_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaDescription($metaDescription)
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaPrefix()
    {
        return $this->_get(self::META_PREFIX);
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaPrefix($metaPrefix)
    {
        return $this->setData(self::META_PREFIX, $metaPrefix);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaSuffix()
    {
        return $this->_get(self::META_SUFFIX);
    }

    /**
     * {@inheritDoc}
     */
    public function setMetaSuffix($metaSuffix)
    {
        return $this->setData(self::META_SUFFIX, $metaSuffix);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerGroups()
    {
        return $this->_get(self::CUSTOMER_GROUPS);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerGroups($customerGroups)
    {
        return $this->setData(self::CUSTOMER_GROUPS, $customerGroups);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductCondition()
    {
        return $this->_get(self::PRODUCT_CONDITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductCondition($productCondition)
    {
        return $this->setData(self::PRODUCT_CONDITION, $productCondition);
    }

    /**
     * Get related product ids
     *
     * @return int[]|null
     */
    public function getRelatedProductIds()
    {
        return $this->_get(self::RELATED_PRODUCT_IDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setRelatedProductIds($relatedProductIds)
    {
        return $this->setData(self::RELATED_PRODUCT_IDS, $relatedProductIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedPostIds()
    {
        return $this->_get(self::RELATED_POST_IDS);
    }

    /**
     * {@inheritdoc$
     */
    public function setRelatedPostIds($relatedPostIds)
    {
        return $this->setData(self::RELATED_POST_IDS, $relatedPostIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedImageFile()
    {
        return $this->_get(self::FEATURED_IMAGE_FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFeaturedImageFile($featuredImageFile)
    {
        return $this->setData(self::FEATURED_IMAGE_FILE, $featuredImageFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedImageMobileFile()
    {
        return $this->_get(self::FEATURED_IMAGE_MOBILE_FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFeaturedImageMobileFile($featuredImageFile)
    {
        return $this->setData(self::FEATURED_IMAGE_MOBILE_FILE, $featuredImageFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedImageTitle()
    {
        return $this->_get(self::FEATURED_IMAGE_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setFeaturedImageTitle($featuredImageTitle)
    {
        return $this->setData(self::FEATURED_IMAGE_TITLE, $featuredImageTitle);
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedImageAlt()
    {
        return $this->_get(self::FEATURED_IMAGE_ALT);
    }

    /**
     * {@inheritdoc}
     */
    public function setFeaturedImageAlt($featuredImageAlt)
    {
        return $this->setData(self::FEATURED_IMAGE_ALT, $featuredImageAlt);
    }

    /**
     * Get featured image link
     *
     * @return string|null
     */
    public function getFeaturedImageLink()
    {
        return $this->_get(self::FEATURED_IMAGE_LINK);
    }

    /**
     * Set featured image link
     *
     * @param string $featuredImageLink
     * @return $this
     */
    public function setFeaturedImageLink($featuredImageLink)
    {
        return $this->setData(self::FEATURED_IMAGE_LINK, $featuredImageLink);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetaTwitterSite()
    {
        return $this->_get(self::META_TWITTER_SITE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetaTwitterSite($metaTwitterSite)
    {
        return $this->setData(self::META_TWITTER_SITE, $metaTwitterSite);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(\Aheadworks\Blog\Api\Data\PostExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
