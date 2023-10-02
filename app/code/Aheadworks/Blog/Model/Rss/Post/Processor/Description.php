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
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;

/**
 * Class Description
 *
 * @package Aheadworks\Blog\Model\Rss\Post\Processor
 */
class Description implements ProcessorInterface
{
    /**
     * @var FeaturedImageInfo
     */
    private $imageInfo;

    /**
     * @param FeaturedImageInfo $imageInfo
     */
    public function __construct(
        FeaturedImageInfo $imageInfo
    ) {
        $this->imageInfo = $imageInfo;
    }

    /**
     * @inheritdoc
     */
    public function process(RssItemInterface $rssItem, PostInterface $post)
    {
        $description = '
                    <table><tr>
                        <td><a href="%s"><img src="%s" border="0" alt="%s" title="%s" ></a></td>
                        <td  style="text-decoration:none;">%s</td>
                    </tr></table>
                ';

        $description = sprintf(
            $description,
            $rssItem->getLink(),
            $this->imageInfo->getImageUrl($post->getFeaturedImageFile()),
            $post->getFeaturedImageAlt(),
            $post->getFeaturedImageTitle(),
            $post->getShortContent()
        );

        $rssItem->setDescription($description);
    }
}
