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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogSearch\Block;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Block\Post as PostBlock;
use Magento\Framework\View\Element\Template;

/**
 * Class PostList
 * @method string getSocialIconsBlock()
 */
class PostList extends Template
{
    /**
     * Retrieves list item html
     *
     * @param string $blockAlias
     * @param PostInterface $post
     * @return string
     */
    public function getItemHtml(string $blockAlias, PostInterface $post)
    {
        $html = '';

        /** @var PostBlock $block */
        $block = $this->getChildBlock($blockAlias);
        if ($block) {
            $block->setPost($post);
            $block->setMode(PostBlock::MODE_LIST_ITEM);

            $html = $block->toHtml();
        }

        return $html;
    }
}
