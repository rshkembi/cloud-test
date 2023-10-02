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
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\Author as RewriteCleaner;

/**
 * Class AuthorDeleteAfterProcessingObserver
 * @package Aheadworks\Blog\Observer\UrlRewrites
 */
class AuthorDeleteAfterProcessingObserver implements ObserverInterface
{
    /**
     * @var RewriteCleaner
     */
    private $rewriteCleaner;

    /**
     * AuthorDeleteAfterProcessingObserver constructor.
     * @param RewriteCleaner $rewriteCleaner
     */
    public function __construct(
        RewriteCleaner $rewriteCleaner
    ) {
        $this->rewriteCleaner = $rewriteCleaner;
    }

    /**
     * Process rewrites deleting after entity deleted
     *
     * @param EventObserver $observer
     * @return $this
     * @throws LocalizedException
     */
    public function execute(EventObserver $observer)
    {
        /** @var AuthorInterface $author */
        $author = $observer->getEvent()->getEntity();

        if ($author) {
            $this->rewriteCleaner->clean($author);
        }

        return $this;
    }
}
