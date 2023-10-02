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

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\ResourceModel\Post\Collection;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassAssign
 * @package Aheadworks\Blog\Controller\Adminhtml\Post
 */
class MassAssign extends AbstractMassAction
{
    /**
     * @inheritdoc
     */
    protected function massAction(Collection $collection)
    {
        $authorId = $this->getRequest()->getParam('assign_author_id');
        $changedRecords = 0;

        /** @var PostInterface $post */
        foreach ($collection->getAllIds() as $postId) {
            try {
                $post = $this->postRepository->get($postId);
                $post->setAuthorId($authorId);
                $this->postRepository->save($post);
                $changedRecords++;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong. A total of %1 record(s) were changed.', $changedRecords)
                );
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
