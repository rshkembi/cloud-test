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
namespace Aheadworks\Blog\Model\Source\Config\Related;

/**
 * Class BlockPosition
 * @package Aheadworks\Blog\Model\Source\Config\Related
 */
class BlockPosition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var string
     */
    const AFTER_POST = 'after_post';
    const AFTER_COMMENTS = 'after_comments';
    const NOT_DISPLAY = 'not_display';

    /**
     * Retrieve block position types as option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::AFTER_POST, 'label' => __('After post')],
            ['value' => self::AFTER_COMMENTS, 'label' => __('After comments')],
            ['value' => self::NOT_DISPLAY, 'label' => __('Don\'t display')]
        ];
    }
}
