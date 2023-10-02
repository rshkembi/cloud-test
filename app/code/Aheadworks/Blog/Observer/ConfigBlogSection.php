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
namespace Aheadworks\Blog\Observer;

use Aheadworks\Blog\Model\Service\IndexService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ConfigBlogSection
 */
class ConfigBlogSection implements ObserverInterface
{
    /**
     * @var IndexService
     */
    private $indexService;

    /**
     * ConfigBlogSection constructor.
     * @param IndexService $indexService
     */
    public function __construct(
        IndexService $indexService
    ) {
        $this->indexService = $indexService;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $changedPaths = $observer->getEvent()->getChangedPaths();
        $this->indexService->processOnConfigChanges($changedPaths);
    }
}