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
namespace Aheadworks\Blog\Model\Source\Post\SharingButtons;

/**
 * Display sharing buttons at source model
 * @package Aheadworks\Blog\Model\Source\Poast
 */
class DisplayAt implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * 'Post' option
     */
    const POST = 1;

    /**
     * 'List of Posts' option
     */
    const POST_LIST = 2;

    /**
     * @var array
     */
    private $options;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [
                [
                    'value' => self::POST,
                    'label' => __('Post')
                ],
                [
                    'value' => self::POST_LIST,
                    'label' => __('List of Posts')
                ]
            ];
        }
        return $this->options;
    }
}
