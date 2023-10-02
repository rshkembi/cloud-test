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

namespace Aheadworks\Blog\Controller\Adminhtml\Import;

use Aheadworks\Blog\Model\Import\Processor\Composite;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Backend\App\Action;

class Start extends Action
{
    /**
     * Import constructor.
     * @param Context $context
     * @param Composite $compositeImport
     */
    public function __construct(
        Context $context,
        private readonly Composite $compositeImport
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        try {
            $this->compositeImport->perform($data);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Sorry, but the data is invalid or the file is not uploaded.'));
        } catch (\Throwable $e) {
            //TODO Need to remove this catcher after magento version 2.4.6 support is completed https://github.com/magento/magento2/issues/37281
            $this->messageManager->addErrorMessage(__('Please provide valid CSV file for import.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
