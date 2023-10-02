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
namespace Aheadworks\Blog\Api\Data;

/**
 * Post interface
 * @api
 */
interface PostInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const URL_KEY = 'url_key';
    const TITLE = 'title';
    const SHORT_CONTENT = 'short_content';
    const CONTENT = 'content';
    const STATUS = 'status';
    const AUTHOR = 'author';
    const AUTHOR_ID = 'author_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const PUBLISH_DATE = 'publish_date';
    const END_DATE = 'end_date';
    const IS_ALLOW_COMMENTS = 'is_allow_comments';
    const IS_FEATURED = 'is_featured';
    const AUTHOR_DISPLAY_MODE = 'author_display_mode';
    const IS_AUTHOR_BADGE_ENABLED = 'is_author_badge_enabled';
    const STORE_IDS = 'store_ids';
    const CATEGORY_IDS = 'category_ids';
    const TAG_IDS = 'tag_ids';
    const CANONICAL_CATEGORY_ID = 'canonical_category_id';
    const TAG_NAMES = 'tag_names';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const META_PREFIX = 'meta_prefix';
    const META_SUFFIX = 'meta_suffix';
    const PRODUCT_CONDITION = 'product_condition';
    const ARE_RELATED_PRODUCTS_ENABLED = 'are_related_products_enabled';
    const RELATED_PRODUCT_IDS = 'related_product_ids';
    const RELATED_POST_IDS = 'related_post_ids';
    const FEATURED_IMAGE_FILE = 'featured_image_file';
    const FEATURED_IMAGE_MOBILE_FILE = 'featured_image_mobile_file';
    const FEATURED_IMAGE_TITLE = 'featured_image_title';
    const FEATURED_IMAGE_ALT = 'featured_image_alt';
    const FEATURED_IMAGE_LINK = 'featured_image_link';
    const META_TWITTER_SITE = 'meta_twitter_site';
    const CUSTOMER_GROUPS = 'customer_groups';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get URL-Key
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Set URL-Key
     *
     * @param string $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get short content
     *
     * @return string|null
     */
    public function getShortContent();

    /**
     * Set short content
     *
     * @param string $shortContent
     * @return $this
     */
    public function setShortContent($shortContent);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get author
     *
     * @return \Aheadworks\Blog\Api\Data\AuthorInterface|null
     */
    public function getAuthor();

    /**
     * Set author
     *
     * @param \Aheadworks\Blog\Api\Data\AuthorInterface $author
     * @return $this
     */
    public function setAuthor($author);

    /**
     * Get author ID
     *
     * @return string|null
     */
    public function getAuthorId();

    /**
     * Set author ID
     *
     * @param string $authorId
     * @return $this
     */
    public function setAuthorId($authorId);

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set creation time
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set update time
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get publish date
     *
     * @return string|null
     */
    public function getPublishDate();

    /**
     * Set publish date
     *
     * @param string $publishDate
     * @return $this
     */
    public function setPublishDate($publishDate);

    /**
     * Get end date
     *
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * Set end date
     *
     * @param string $endDate
     * @return $this
     */
    public function setEndDate($endDate): PostInterface;

    /**
     * Get is allowed comments
     *
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsAllowComments();

    /**
     * Set is allowed comments
     *
     * @param bool $isAllowComments
     * @return $this
     */
    public function setIsAllowComments($isAllowComments);

    /**
     * Get is featured post
     *
     * @return bool
     */
    public function getIsFeatured();

    /**
     * Set is featured post
     *
     * @param bool $isFeatured
     * @return $this
     */
    public function setIsFeatured($isFeatured);

    /**
     * Get author display mode
     *
     * @return int
     */
    public function getAuthorDisplayMode();

    /**
     * Set author display mode
     *
     * @param int $authorDisplayMode
     * @return $this
     */
    public function setAuthorDisplayMode($authorDisplayMode);

    /**
     * Get are related products enabled
     *
     * @return bool
     */
    public function getAreRelatedProductsEnabled();

    /**
     * Set are related products enabled
     *
     * @param int $flag
     * @return $this
     */
    public function setAreRelatedProductsEnabled($flag);

    /**
     * Get is author badge enabled
     *
     * @return bool
     */
    public function getIsAuthorBadgeEnabled();

    /**
     * Set is author badge enabled
     *
     * @param int $flag
     * @return $this
     */
    public function setIsAuthorBadgeEnabled($flag);

    /**
     * Get store IDs
     *
     * @return int[]
     */
    public function getStoreIds();

    /**
     * Set store IDs
     *
     * @param int[] $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds);

    /**
     * Get category IDs
     *
     * @return int[]|null
     */
    public function getCategoryIds();

    /**
     * Set category IDs
     *
     * @param int[] $categoryIds
     * @return $this
     */
    public function setCategoryIds($categoryIds);

    /**
     * Get tag IDs
     *
     * @return int[]|null
     */
    public function getTagIds();

    /**
     * Set tag IDs
     *
     * @param int[] $tagIds
     * @return $this
     */
    public function setTagIds($tagIds);

    /**
     * Get canonical category id
     *
     * @return int|null
     */
    public function getCanonicalCategoryId();

    /**
     * Set canonical category id
     *
     * @param int $canonicalCategoryId
     * @return $this
     */
    public function setCanonicalCategoryId($canonicalCategoryId);

    /**
     * Get tag names
     *
     * @return string[]|null
     */
    public function getTagNames();

    /**
     * Set tag names
     *
     * @param string[] $tagNames
     * @return $this
     */
    public function setTagNames($tagNames);

    /**
     * Get meta title
     *
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * Set meta title
     *
     * @param string $metaTitle
     * @return $this
     */
    public function setMetaTitle($metaTitle);

    /**
     * Get meta keywords
     *
     * @return string|null
     */
    public function getMetaKeywords();

    /**
     * Set meta keywords
     *
     * @param string $metaKeywords
     * @return $this
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Get meta description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Set meta description
     *
     * @param string $metaDescription
     * @return $this
     */
    public function setMetaDescription($metaDescription);

    /**
     * Get meta prefix
     *
     * @return string|null
     */
    public function getMetaPrefix();

    /**
     * Set meta prefix
     *
     * @param string $metaPrefix
     * @return $this
     */
    public function setMetaPrefix($metaPrefix);

    /**
     * Get meta suffix
     *
     * @return string|null
     */
    public function getMetaSuffix();

    /**
     * Set meta suffix
     *
     * @param string $metaSuffix
     * @return $this
     */
    public function setMetaSuffix($metaSuffix);

    /**
     * Get product condition
     *
     * @return \Aheadworks\Blog\Api\Data\ConditionInterface
     */
    public function getProductCondition();

    /**
     * Set product condition
     *
     * @param \Aheadworks\Blog\Api\Data\ConditionInterface $productCondition
     * @return $this
     */
    public function setProductCondition($productCondition);

    /**
     * Get related product ids
     *
     * @return int[]|null
     */
    public function getRelatedProductIds();

    /**
     * Set related product ids
     *
     * @param int[] $relatedProductIds
     * @return $this
     */
    public function setRelatedProductIds($relatedProductIds);

    /**
     * Get related post ids
     *
     * @return int[]|null
     */
    public function getRelatedPostIds();

    /**
     * Set related post ids
     *
     * @param int[] $relatedPostIds
     * @return $this
     */
    public function setRelatedPostIds($relatedPostIds);

    /**
     * Get featured image file
     *
     * @return string|null
     */
    public function getFeaturedImageFile();

    /**
     * Set featured image file
     *
     * @param string $featuredImageFile
     * @return $this
     */
    public function setFeaturedImageFile($featuredImageFile);

    /**
     * Get featured image mobile file
     *
     * @return string|null
     */
    public function getFeaturedImageMobileFile();

    /**
     * Set featured image mobile file
     *
     * @param string $featuredImageFile
     * @return $this
     */
    public function setFeaturedImageMobileFile($featuredImageFile);

    /**
     * Get featured image title
     *
     * @return string|null
     */
    public function getFeaturedImageTitle();

    /**
     * Set featured image title
     *
     * @param string $featuredImageTitle
     * @return $this
     */
    public function setFeaturedImageTitle($featuredImageTitle);

    /**
     * Get featured image alt
     *
     * @return string|null
     */
    public function getFeaturedImageAlt();

    /**
     * Set featured image alt
     *
     * @param string $featuredImageAlt
     * @return $this
     */
    public function setFeaturedImageAlt($featuredImageAlt);

    /**
     * Get featured image link
     *
     * @return string|null
     */
    public function getFeaturedImageLink();

    /**
     * Set featured image link
     *
     * @param string $featuredImageLink
     * @return $this
     */
    public function setFeaturedImageLink($featuredImageLink);

    /**
     * Get meta twitter site
     *
     * @return string|null
     */
    public function getMetaTwitterSite();

    /**
     * Set meta twitter site
     *
     * @param string $metaTwitterSite
     * @return $this
     */
    public function setMetaTwitterSite($metaTwitterSite);

    /**
     * Get allowed customer groups for post
     *
     * @return string
     */
    public function getCustomerGroups();

    /**
     * Set allowed customer groups for post
     *
     * @param int[] $customerGroups
     * @return $this
     */
    public function setCustomerGroups($customerGroups);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Blog\Api\Data\PostExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Blog\Api\Data\PostExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Aheadworks\Blog\Api\Data\PostExtensionInterface $extensionAttributes);
}
