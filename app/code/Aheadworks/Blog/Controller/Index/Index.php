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
namespace Aheadworks\Blog\Controller\Index;

use Aheadworks\Blog\Controller\Action;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Index
 * @package Aheadworks\Blog\Controller\Index
 */
class Index extends Action
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $pageConfig = $resultPage->getConfig();

        if ($tagId = (int)$this->getRequest()->getParam('tag_id')) {
            try {
                $tag = $this->tagRepository->get($tagId);
                $pageConfig->getTitle()->set(__("Tagged with '%1'", $tag->getName()));
            } catch (LocalizedException $e) {
                $pageConfig->getTitle()->set($this->getBlogTitle());
            }
        } else {
            $pageConfig->getTitle()->set($this->getBlogTitle());
        }
        if ($this->areMetaTagsEnabled()) {
            $pageConfig->setMetadata('description', $this->getBlogMetaDescription());
            $pageConfig->setMetadata('keywords', $this->getBlogMetaKeywords());
        }

        $this->canonicalIncluder->includeOnBlogPage($pageConfig);

        return $resultPage;
    }
}
