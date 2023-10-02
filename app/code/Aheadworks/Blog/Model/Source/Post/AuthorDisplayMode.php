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
namespace Aheadworks\Blog\Model\Source\Post;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class AuthorDisplayMode
 */
class AuthorDisplayMode implements OptionSourceInterface
{
    const USE_DEFAULT_OPTION = -1;
    const DISPLAY = 1;
    const DISPLAY_NONE = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DISPLAY,
                'label' => __('Yes')
            ],
            [
                'value' => self::DISPLAY_NONE,
                'label' => __('No')
            ]
        ];
    }
}