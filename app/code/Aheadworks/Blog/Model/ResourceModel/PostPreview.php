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
namespace Aheadworks\Blog\Model\ResourceModel;

use Aheadworks\Blog\Api\Data\PostPreviewInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class PostPreview
 * @package Aheadworks\Blog\Model\ResourceModel
 */
class PostPreview extends AbstractDb
{
    /**#@+
     * Constants defined for table names
     */
    const BLOG_POST_PREVIEW_TABLE = 'aw_blog_post_preview';
    /**#@-*/

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::BLOG_POST_PREVIEW_TABLE, PostPreviewInterface::ID);
    }
}
