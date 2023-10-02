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
namespace Aheadworks\Blog\Controller\Adminhtml\Widget;

use Aheadworks\Blog\Model\Widget\HtmlProcessor;
use Aheadworks\Blog\Model\Widget\RequestDataProvider;
use Magento\Backend\App\Action;
use Aheadworks\Blog\Model\Widget\InstanceFactory;

/**
 * Class Display
 */
class Display extends Action
{
    /**
     * @var InstanceFactory
     */
    private $widgetInstanceFactory;

    /**
     * @var HtmlProcessor
     */
    private $htmlProcessor;

    /**
     * @var RequestDataProvider
     */
    private $requestDataProvider;

    /**
     * Display constructor.
     * @param Action\Context $context
     * @param InstanceFactory $widgetInstanceFactory
     * @param HtmlProcessor $htmlProcessor
     * @param RequestDataProvider $requestDataProvider
     */
    public function __construct(
        Action\Context $context,
        InstanceFactory $widgetInstanceFactory,
        HtmlProcessor $htmlProcessor,
        RequestDataProvider $requestDataProvider
    ) {
        parent::__construct($context);
        $this->widgetInstanceFactory = $widgetInstanceFactory;
        $this->htmlProcessor = $htmlProcessor;
        $this->requestDataProvider = $requestDataProvider;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $code = $this->requestDataProvider->getCode();
        $displayType = $this->requestDataProvider->getDisplayType();

        $widgetInstance = $this->widgetInstanceFactory->create();
        $chooserInstance = $widgetInstance->getChooserBlockByType($code, $displayType);
        $errors = $widgetInstance->getErrors();

        if (!$errors && $chooserInstance) {
            $html = $this->htmlProcessor->execute($chooserInstance, $this->requestDataProvider->getDefaultData());
        } else {
            $this->messageManager->addComplexErrorMessage(
                'widgetInstanceErrorMessage',
                [
                    'errors' => $errors
                ]
            );

            $html = $this->_view->getLayout()->getMessagesBlock()->getGroupedHtml();
        }

        return $this->getResponse()->appendBody($html);
    }
}