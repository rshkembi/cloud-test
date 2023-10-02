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
namespace Aheadworks\Blog\Model\Indexer;

/**
 * Class ProductPost
 * @package Aheadworks\Blog\Model\Indexer
 */
class ProductPost implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var ProductPost\Action\Row
     */
    private $productPostIndexerRow;

    /**
     * @var ProductPost\Action\Rows
     */
    private $productPostIndexerRows;

    /**
     * @var ProductPost\Action\Full
     */
    private $productPostIndexerFull;

    /**
     * @param ProductPost\Action\Row $productPostIndexerRow
     * @param ProductPost\Action\Rows $productPostIndexerRows
     * @param ProductPost\Action\Full $productPostIndexerFull
     */
    public function __construct(
        ProductPost\Action\Row $productPostIndexerRow,
        ProductPost\Action\Rows $productPostIndexerRows,
        ProductPost\Action\Full $productPostIndexerFull
    ) {
        $this->productPostIndexerRow = $productPostIndexerRow;
        $this->productPostIndexerRows = $productPostIndexerRows;
        $this->productPostIndexerFull = $productPostIndexerFull;
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     *
     * @return void
     */
    public function execute($ids)
    {
        $this->productPostIndexerRows->execute($ids);
    }

    /**
     * Execute full indexation
     *
     * @return void
     */
    public function executeFull()
    {
        $this->productPostIndexerFull->execute();
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param int[] $ids
     *
     * @return void
     */
    public function executeList(array $ids)
    {
        $this->productPostIndexerRows->execute($ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     *
     * @return void
     */
    public function executeRow($id)
    {
        $this->productPostIndexerRow->execute($id);
    }
}
