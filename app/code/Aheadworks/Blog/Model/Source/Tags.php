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
namespace Aheadworks\Blog\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Aheadworks\Blog\Model\ResourceModel\Tag\CollectionFactory as TagCollectionFactory;

/**
 * Class Tags
 * @package Aheadworks\Blog\Model\Source
 */
class Tags implements OptionSourceInterface
{
    /**
     * @var \Aheadworks\Blog\Model\ResourceModel\Tag\Collection
     */
    private $tagCollection;

    /**
     * @var array
     */
    private $options;

    /**
     * @param TagCollectionFactory $tagCollectionFactory
     */
    public function __construct(TagCollectionFactory $tagCollectionFactory)
    {
        $this->tagCollection = $tagCollectionFactory->create();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->tagCollection->toOptionArray();
        }
        return $this->options;
    }
}
