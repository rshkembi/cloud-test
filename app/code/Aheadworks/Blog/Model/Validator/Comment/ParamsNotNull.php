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

namespace Aheadworks\Blog\Model\Validator\Comment;

use Magento\Framework\Validator\AbstractValidator;

class ParamsNotNull extends AbstractValidator
{
    /**
     * @param array $validateFields
     */
    public function __construct(private readonly array $validateFields = [])
    {}

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $model
     * @return bool
     */
    public function isValid($model): bool
    {
        $this->_clearMessages();

        foreach ($this->validateFields as $validateField) {
            if (is_null($model->getData($validateField))) {
                $this->_addMessages(
                    [
                        __('Please, specify param "%1"', $validateField)
                    ]
                );
            }
        }

        return empty($this->getMessages());
    }
}
