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
namespace Aheadworks\Blog\Block;

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class PostImage
 *
 * @method $this setPost(PostInterface $post)
 * @method PostInterface getPost()
 * @method $this setImgClass(string $class)
 * @method string getImgClass()
 */
class PostImage extends Template
{
    protected $_template = 'post_image.phtml';
}
