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

use Aheadworks\Blog\Model\ResourceModel\Category\Collection;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassStatus
 * @package Aheadworks\Blog\Controller\Adminhtml\Category
 */
class MassStatus extends AbstractMassAction
{
    /**
     * @inheritdoc
     */
    protected function massAction(Collection $collection)
    {
        $status = (int) $this->getRequest()->getParam('status');
        $changedRecords = 0;

        foreach ($collection->getAllIds() as $categoryId) {
            try {
                $categoryModel = $this->categoryRepository->get($categoryId);
            } catch (\Exception $e) {
                $categoryModel = null;
            }
            if ($categoryModel) {
                $categoryModel->setData('status', $status);
                $this->categoryRepository->save($categoryModel);
                $changedRecords++;
            }
        }
        if ($changedRecords) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were changed.', $changedRecords));
        } else {
            $this->messageManager->addSuccessMessage(__('No records were changed.'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
