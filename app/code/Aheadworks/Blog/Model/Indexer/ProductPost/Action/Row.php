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
namespace Aheadworks\Blog\Model\Indexer\ProductPost\Action;

/**
 * Class Row
 * @package Aheadworks\Blog\Model\Indexer\ProductPost\Action
 */
class Row extends \Aheadworks\Blog\Model\Indexer\ProductPost\AbstractAction
{
    /**
     * Execute Row reindex
     *
     * @param int|null $id
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($id = null)
    {
        if (!isset($id) || empty($id)) {
            throw new \Magento\Framework\Exception\InputException(
                __('We can\'t rebuild the index for an undefined entity.')
            );
        }
        try {
            $this->resourceProductPostIndexer->reindexRows([$id]);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }
}
