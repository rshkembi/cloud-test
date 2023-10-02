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
namespace Aheadworks\Blog\Model\Service;

use Aheadworks\Blog\Model\Indexer\ProductPost\ConfigChecker;
use Aheadworks\Blog\Model\Indexer\ProductPost\IndexManagementInterface;
use Aheadworks\Blog\Model\Indexer\ProductPost\Processor as ProductPostProcessor;

class IndexService implements IndexManagementInterface
{
    /**
     * @var ProductPostProcessor
     */
    private $productPostProcessor;

    /**
     * @var ConfigChecker
     */
    private $configChecker;

    /**
     * IndexService constructor.
     * @param ProductPostProcessor $productPostProcessor
     * @param ConfigChecker $configChecker
     */
    public function __construct(
        ProductPostProcessor $productPostProcessor,
        ConfigChecker $configChecker
    ) {
        $this->productPostProcessor = $productPostProcessor;
        $this->configChecker = $configChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function processOnConfigChanges($changedPaths)
    {
        if ($this->configChecker->isChanged($changedPaths)) {
            $this->invalidateIndex();
        }
    }

    /**
     * Invalidate product post index
     *
     * @return bool
     */
    private function invalidateIndex()
    {
        try {
            $this->productPostProcessor->markIndexerAsInvalid();
            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}