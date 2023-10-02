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
namespace Aheadworks\Blog\Model\Source\Category\Options;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class DisplayModes
 */
class DisplayModes extends AbstractSource
{
    const DM_BLOG_POSTS_ONLY = 'blog_posts_only';
    const DM_STATIC_BLOCK_ONLY = 'static_block_only';
    const DM_MIXED = 'posts_and_static_block';

    /**
     * @var array
     */
    private $options;

    /**
     * Retrieve display modes as option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DM_BLOG_POSTS_ONLY,
                'label' => 'Blog Posts only'
            ],
            [
                'value' => self::DM_STATIC_BLOCK_ONLY,
                'label' => 'Static Block only'
            ],
            [
                'value' => self::DM_MIXED,
                'label' => 'Static Block and Blog Posts'
            ]
        ];
    }

    /**
     * Return all display mode options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->options) {
            $this->options = $this->toOptionArray();
        }

        return $this->options;
    }
}