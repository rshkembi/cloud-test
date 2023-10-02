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

namespace Aheadworks\Blog\Model\Source\Email;

use Magento\Config\Model\Config\Source\Email\Template as EmailTemplateSourceModel;

class Template extends EmailTemplateSourceModel
{
    /**
     * Option value to forbid email sending
     */
    public const DO_NOT_SEND_EMAIL_VALUE = '';

    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionList = parent::toOptionArray();
        $optionList[] = [
            'value' => self::DO_NOT_SEND_EMAIL_VALUE,
            'label' => __('Do not send')
        ];

        return $optionList;
    }
}
