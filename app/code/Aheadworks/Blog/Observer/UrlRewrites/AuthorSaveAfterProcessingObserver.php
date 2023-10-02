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
namespace Aheadworks\Blog\Observer\UrlRewrites;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Aheadworks\Blog\Api\Data\AuthorInterfaceFactory as AuthorFactory;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\UrlRewrites\Service\Author as UrlRewritesAuthorService;

/**
 * Class AuthorSaveAfterProcessingObserver
 * @package Aheadworks\Blog\Observer\UrlRewrites
 */
class AuthorSaveAfterProcessingObserver implements ObserverInterface
{
    /**
     * @var UrlRewritesAuthorService
     */
    private $urlRewritesAuthorService;

    /**
     * @var AuthorFactory
     */
    private $authorFactory;

    /**
     * AuthorSaveAfterProcessingObserver constructor.
     * @param AuthorFactory $authorFactory
     * @param UrlRewritesAuthorService $urlRewritesAuthorService
     */
    public function __construct(
        AuthorFactory $authorFactory,
        UrlRewritesAuthorService $urlRewritesAuthorService
    ) {
        $this->authorFactory = $authorFactory;
        $this->urlRewritesAuthorService = $urlRewritesAuthorService;
    }

    /**
     * Process rewrites after author saved
     *
     * @param EventObserver $observer
     * @return $this
     * @throws LocalizedException
     */
    public function execute(EventObserver $observer)
    {
       /** @var AuthorInterface $author */
        $author = $observer->getEvent()->getEntity();

        if ($author && !empty($author->getOrigData())) {
           /** @var AuthorInterface $origAuthor */
            $origAuthor = $this->authorFactory->create(['data' => $author->getOrigData()]);
        } else {
            $origAuthor = null;
        }

        if ($author) {
            $this->urlRewritesAuthorService->updateRewrites($author, $origAuthor);
        }

        return $this;
    }
}
