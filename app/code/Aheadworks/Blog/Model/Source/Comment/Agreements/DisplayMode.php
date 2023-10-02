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

namespace Aheadworks\Blog\Model\Source\Comment\Agreements;

use Magento\Framework\Data\OptionSourceInterface;

class DisplayMode implements OptionSourceInterface
{
    /**#@+
     * Agreements display mode values
     */
    public const GUESTS_ONLY = 1;
    public const EVERYONE = 2;
    /**#@-*/

    /**
     * @var array
     */
    private $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $this->options = [
            [
                'value' => self::GUESTS_ONLY,
                'label' => __('Guests only')
            ],
            [
                'value' => self::EVERYONE,
                'label' => __('Everyone')
            ],
        ];

        return $this->options;
    }
}
