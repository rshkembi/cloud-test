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
namespace Aheadworks\Blog\Model\Rss\Post;

use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Class RssItem
 *
 * @package Aheadworks\Blog\Model\Rss\Post
 */
class RssItem extends AbstractExtensibleObject implements RssItemInterface
{
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function getLink()
    {
        return $this->_get(self::LINK);
    }

    /**
     * @inheritdoc
     */
    public function setLink($link)
    {
        return $this->setData(self::LINK, $link);
    }

    /**
     * @inheritdoc
     */
    public function getDateCreated()
    {
        return $this->_get(self::DATE_CREATED);
    }

    /**
     * @inheritdoc
     */
    public function setDateCreated($dateCreated)
    {
        return $this->setData(self::DATE_CREATED, $dateCreated);
    }
}
