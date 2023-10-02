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

namespace Aheadworks\Blog\Model\Source\Captcha;

use Magento\Framework\Data\OptionSourceInterface;

class DisplayMode implements OptionSourceInterface
{
    /**#@+
     * Captcha display mode list
     */
    public const GUEST_COMMENT_SUBMIT = 1;
    public const CUSTOMER_COMMENT_SUBMIT = 2;
    public const GUEST_REPLY_COMMENT_SUBMIT = 3;
    public const CUSTOMER_REPLY_COMMENT_SUBMIT = 4;
    /**#@-*/

    /**
     * @var array
     */
    private array $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $this->options = [
            [
                'value' => self::GUEST_COMMENT_SUBMIT,
                'label' => __('Enable for Comments from Guests')
            ],
            [
                'value' => self::CUSTOMER_COMMENT_SUBMIT,
                'label' => __('Enable for Comments from Logged in Users')
            ],
            [
                'value' => self::GUEST_REPLY_COMMENT_SUBMIT,
                'label' => __('Enable for Reply Comments for Guests')
            ],
            [
                'value' => self::CUSTOMER_REPLY_COMMENT_SUBMIT,
                'label' => __('Enable for Reply Comments for Logged in Users')
            ]
        ];

        return $this->options;
    }
}
