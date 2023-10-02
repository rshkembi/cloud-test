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
namespace Aheadworks\Blog\Model\Post\StructuredData;

use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Interface ProviderInterface
 *
 * @package Aheadworks\Blog\Model\Post\StructuredData
 */
interface ProviderInterface
{
    /**
     * Get prepared structured data array for the blog post
     *
     * @param PostInterface $post
     * @return array
     */
    public function getData($post);
}
