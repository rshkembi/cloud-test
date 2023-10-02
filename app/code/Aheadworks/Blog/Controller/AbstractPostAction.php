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

namespace Aheadworks\Blog\Controller;

use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;

abstract class AbstractPostAction extends AbstractFrontendAction
{
    /**
     * @param Context $context
     * @param Config $config
     * @param FormKeyValidator $formKeyValidator
     */
    public function __construct(
        Context $context,
        Config $config,
        protected FormKeyValidator $formKeyValidator
    ) {
        parent::__construct($context, $config);
    }

    /**
     * Validate form
     *
     * @return void
     * @throws LocalizedException
     */
    protected function validate(): void
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            throw new LocalizedException(__('Invalid Form Key. Please refresh the page.'));
        }
    }
}
