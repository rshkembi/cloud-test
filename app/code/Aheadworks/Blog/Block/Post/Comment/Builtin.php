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

namespace Aheadworks\Blog\Block\Post\Comment;

use Aheadworks\Blog\Block\Base;
use Magento\Framework\View\Element\Template;

/**
 * Builtin integration block
 *
 * @method int|null getPageIdentifier()
 * @method string|null getPageUrl()
 * @method string|null getPageTitle()
 *
 * @method $this setPageIdentifier(int)
 * @method $this setPageUrl(string)
 * @method $this setPageTitle(string)
 *
 */
class Builtin extends Base
{
    /**
     * Retrieves comment embed code html
     *
     * @return string
     */
    public function getCommentHeaderHtml()
    {
        /** @var Template $headerBlock */
        $headerBlock =  $this->getChildBlock('aw_blog_post_comment_header');

        return $headerBlock ? $headerBlock->toHtml() : '';
    }

    /**
     * Retrieves comment embed code html
     *
     * @return string
     */
    public function getCommentListingHtml()
    {
        $html = '';
        /** @var Template $commentListing */
        $commentListing = $this->getChildBlock('aw.blog.comment.listing');
        if ($commentListing) {
            $html = $commentListing
                ->setPageIdentifier((int)$this->getPageIdentifier())
                ->toHtml();
        }

        return $html;
    }
}
