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

namespace Aheadworks\Blog\Controller\Email\Notification\Settings;

use Aheadworks\Blog\Controller\AbstractPostAction;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Data\OperationInterface as DataOperationInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Context;

class Save extends AbstractPostAction
{
    /**
     * @param Context $context
     * @param Config $config
     * @param FormKeyValidator $formKeyValidator
     * @param DataOperationInterface $dataOperation
     */
    public function __construct(
        Context $context,
        Config $config,
        FormKeyValidator $formKeyValidator,
        private readonly DataOperationInterface $dataOperation
    ) {
        parent::__construct($context, $config, $formKeyValidator);
    }

    /**
     * Save notification settings
     *
     * @return Redirect
     */
    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();

        if (!empty($postData)) {
            try {
                $this->validate();

                $this->dataOperation->execute($postData);

                $this->messageManager->addSuccessMessage(
                    __("Subscription settings have been successfully saved.")
                );
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving subscription settings.')
                );
            }
        }

        return $this->getPreparedRedirect();
    }

    /**
     * Retrieve redirect to the current page
     *
     * @return Redirect
     */
    private function getPreparedRedirect(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
