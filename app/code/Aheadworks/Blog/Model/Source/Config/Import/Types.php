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
namespace Aheadworks\Blog\Model\Source\Config\Import;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Types
 */
class Types implements OptionSourceInterface
{
    const POSTS = 'blog_posts';
    const CATEGORIES = 'blog_categories';
    const AUTHORS = 'blog_authors';

    /**
     * @var array
     */
    private $options;

    /**
     * @return array[]
     */
    public function getAllOptions()
    {
        return [
            [
                'value' => self::POSTS,
                'label' => 'Posts'
            ],
            [
                'value' => self::CATEGORIES,
                'label' => 'Categories'
            ],
            [
                'value' => self::AUTHORS,
                'label' => 'Authors'
            ]
        ];
    }

    /**
     * @return array|void
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->getAllOptions();
        }

        return $this->options;
    }
}