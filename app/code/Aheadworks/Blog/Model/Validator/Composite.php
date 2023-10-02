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

namespace Aheadworks\Blog\Model\Validator;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Validator\AbstractValidator;

class Composite extends AbstractValidator
{
    /**
     * @param AbstractValidator[] $validatorList
     */
    public function __construct(
        private readonly array $validatorList = []
    ) {
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     *
     * @param CommentInterface $model
     * @return bool
     */
    public function isValid($model)
    {
        $this->_clearMessages();

        foreach ($this->validatorList as $validator) {
            if (!$validator->isValid($model)) {
                $this->_addMessages($validator->getMessages());
            }
        }

        return empty($this->getMessages());
    }
}
