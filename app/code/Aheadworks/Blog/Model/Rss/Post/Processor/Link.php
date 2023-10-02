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
namespace Aheadworks\Blog\Model\Rss\Post\Processor;

use Aheadworks\Blog\Model\Rss\Post\RssItemInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Url as BlogUrl;

/**
 * Class Link
 *
 * @package Aheadworks\Blog\Model\Rss\Post\Processor
 */
class Link implements ProcessorInterface
{
    /**
     * @var BlogUrl
     */
    protected $blogUrl;

    /**
     * @param BlogUrl $blogUrl
     */
    public function __construct(
        BlogUrl $blogUrl
    ) {
        $this->blogUrl = $blogUrl;
    }

    /**
     * @inheritdoc
     */
    public function process(RssItemInterface $rssItem, PostInterface $post)
    {
        $rssItem->setLink($this->blogUrl->getPostUrl($post));
    }
}
