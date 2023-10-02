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
 * Class BlockLayout
 * @package Aheadworks\Blog\Model\Source\Config\Related
 */
class BlockLayout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var string
     */
    const SINGLE_ROW_VALUE = 'single_row';
    const MULTIPLE_ROWS_VALUE = 'multiple_rows';
    const SLIDER_VALUE = 'slider';

    /**
     * Retrieve block layout types as option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SINGLE_ROW_VALUE, 'label' => __('Products aligned in single row')],
            ['value' => self::MULTIPLE_ROWS_VALUE, 'label' => __('Products aligned in multiple rows')],
            ['value' => self::SLIDER_VALUE, 'label' => __('Slider')]
        ];
    }
}
