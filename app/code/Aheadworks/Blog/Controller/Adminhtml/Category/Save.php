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
namespace Aheadworks\Blog\Controller\Adminhtml\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Framework\Message\Error;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterfaceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface as DataProcessorInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class Save
 * @package Aheadworks\Blog\Controller\Adminhtml\Category
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::categories';

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var CategoryInterfaceFactory
     */
    private $categoryDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataProcessorInterface
     */
    private $postDataProcessor;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param Context $context
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryInterfaceFactory $categoryDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataProcessorInterface $postDataProcessor
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository,
        CategoryInterfaceFactory $categoryDataFactory,
        DataObjectHelper $dataObjectHelper,
        DataProcessorInterface $postDataProcessor,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->categoryRepository = $categoryRepository;
        $this->categoryDataFactory = $categoryDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->postDataProcessor = $postDataProcessor;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save category action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($categoryData = $this->getRequest()->getPostValue()) {
            $categoryData = $this->postDataProcessor->process($categoryData);
            $categoryId = isset($categoryData['id']) ? $categoryData['id'] : false;
            try {
                $categoryDataObject = $categoryId
                    ? $this->categoryRepository->get($categoryId)
                    : $this->categoryDataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $categoryDataObject,
                    $categoryData,
                    CategoryInterface::class
                );
                $category = $this->categoryRepository->save($categoryDataObject);
                $this->dataPersistor->clear('aw_blog_category');
                $this->messageManager->addSuccessMessage(__('The category was successfully saved.'));
                $back = $this->getRequest()->getParam('back');
                if ($back == 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/' . $back,
                        [
                            'id' => $category->getId(),
                            '_current' => true
                        ]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Validator\Exception $exception) {
                $messages = $exception->getMessages();
                if (empty($messages)) {
                    $messages = [$exception->getMessage()];
                }
                foreach ($messages as $message) {
                    if (!$message instanceof Error) {
                        $message = new Error($message);
                    }
                    $this->messageManager->addMessage($message);
                }
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving the category.')
                );
            }
            $this->dataPersistor->set('aw_blog_category', $categoryData);
            if ($categoryId) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $categoryId, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
