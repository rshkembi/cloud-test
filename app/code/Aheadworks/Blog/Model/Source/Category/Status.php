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
namespace Aheadworks\Blog\Model\Source\Category;

/**
 * Category Status source model
 * @package Aheadworks\Blog\Model\Source\Category
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * 'Enabled' status
     */
    const ENABLED = 1;

    /**
     * 'Disabled' status
     */
    const DISABLED = 0;

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
                    'value' => self::DISABLED,
                    'label' => __('Disabled')
                ],
                [
                    'value' => self::ENABLED,
                    'label' => __('Enabled')
                ]
            ];
        }
        return $this->options;
    }
}
