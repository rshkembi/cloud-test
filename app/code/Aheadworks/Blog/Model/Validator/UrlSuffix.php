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
namespace Aheadworks\Blog\Model\Validator;

use Magento\Framework\Validator\AbstractValidator;

/**
 * Class UrlSuffix
 * @package Aheadworks\Blog\Model\Validator
 */
class UrlSuffix extends AbstractValidator
{
    /**
     * @param string $urlSuffixString
     * @return bool
     */
    public function isValid($urlSuffixString)
    {
        $this->_clearMessages();

        if (!preg_match('/^[a-z0-9\s_.\/-]*$/', (string)$urlSuffixString)) {
            $message = __('String contains disallowed characters');
            $this->_addMessages([$message]);
        }

        return empty($this->getMessages());
    }
}
