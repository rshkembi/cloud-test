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
namespace Aheadworks\Blog\Model\System\Config;

use Magento\Framework\App\Config\Value;

/**
 * Class TwitterValue
 * @package Aheadworks\Blog\Model\System\Config
 */
class TwitterValue extends Value
{
    const AT_CHARACTER = '@';

    /**
     * Check and insert @ character at the beginning of value
     *
     * @return void
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        if ($value && ($value[0] != self::AT_CHARACTER)) {
            $value = self::AT_CHARACTER . $value;
        }

        $this->setValue($value);
    }
}
