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

use Aheadworks\Blog\Api\Data\TagCloudItemInterface;
use Aheadworks\Blog\Api\Data\TagCloudItemExtensionInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Tag cloud item data model
 * @codeCoverageIgnore
 */
class TagCloudItem extends AbstractExtensibleObject implements TagCloudItemInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return $this->_get(self::TAG);
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($tag)
    {
        return $this->setData(self::TAG, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getPostCount()
    {
        return $this->_get(self::POST_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPostCount($postCount)
    {
        return $this->setData(self::POST_COUNT, $postCount);
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
    public function setExtensionAttributes(TagCloudItemExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
