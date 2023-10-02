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

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class Author
 * @package Aheadworks\Blog\Model\Data
 * @codeCoverageIgnore
 */
class Author extends AbstractExtensibleObject implements AuthorInterface
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
    public function getFirstname()
    {
        return $this->_get(self::FIRSTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstname($name)
    {
        return $this->setData(self::FIRSTNAME, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname()
    {
        return $this->_get(self::LASTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($name)
    {
        return $this->setData(self::LASTNAME, $name);
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
    public function getJobPosition()
    {
        return $this->_get(self::JOB_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setJobPosition($jobPosition)
    {
        return $this->setData(self::JOB_POSITION, $jobPosition);
    }

    /**
     * {@inheritdoc}
     */
    public function getImageFile()
    {
        return $this->_get(self::IMAGE_FILE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageFile($file)
    {
        return $this->setData(self::IMAGE_FILE, $file);
    }

    /**
     * {@inheritdoc}
     */
    public function getShortBio()
    {
        return $this->_get(self::SHORT_BIO);
    }

    /**
     * {@inheritdoc}
     */
    public function setShortBio($shortBio)
    {
        return $this->setData(self::SHORT_BIO, $shortBio);
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterId()
    {
        return $this->_get(self::TWITTER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setTwitterId($twitterId)
    {
        return $this->setData(self::TWITTER_ID, $twitterId);
    }

    /**
     * {@inheritdoc}
     */
    public function getFacebookId()
    {
        return $this->_get(self::FACEBOOK_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setFacebookId($facebookId)
    {
        return $this->setData(self::FACEBOOK_ID, $facebookId);
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedinId()
    {
        return $this->_get(self::LINKEDIN_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setLinkedinId($linkedinId)
    {
        return $this->setData(self::LINKEDIN_ID, $linkedinId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPostsCount()
    {
        return $this->_get(self::POSTS_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPostsCount($count)
    {
        return $this->setData(self::POSTS_COUNT, $count);
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
    public function setExtensionAttributes(\Aheadworks\Blog\Api\Data\AuthorExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
