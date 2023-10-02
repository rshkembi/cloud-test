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
declare(strict_types=1);

namespace Aheadworks\Blog\Model\Source\Config\Comments;

use Magento\Framework\Data\OptionSourceInterface;

class Service implements OptionSourceInterface
{
    public const DISQUS = 'disqus';
    public const BUILT_IN = 'built-in';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DISQUS,
                'label' => __('Disqus')
            ],
            [
                'value' => self::BUILT_IN,
                'label' => __('Built-in')
            ]
        ];
    }
}
