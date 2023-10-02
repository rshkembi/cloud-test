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
namespace Aheadworks\Blog\Controller\Adminhtml\Post;

use Aheadworks\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDelete
 * @package Aheadworks\Blog\Controller\Adminhtml\Post
 */
class MassDelete extends AbstractMassAction
{
    /**
     * @inheritdoc
     */
    protected function massAction(Collection $collection)
    {
        $deletedRecords = 0;
        foreach ($collection->getAllIds() as $postId) {
            $this->postRepository->deleteById($postId);
            $deletedRecords++;
        }
        if ($deletedRecords) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were deleted.', $deletedRecords));
        } else {
            $this->messageManager->addSuccessMessage(__('No records were deleted.'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
