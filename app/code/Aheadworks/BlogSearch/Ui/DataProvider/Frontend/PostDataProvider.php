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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogSearch\Ui\DataProvider\Frontend;

use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\CollectionFactory as FulltextCollectionFactory;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection as FulltextCollection;
use Aheadworks\BlogSearch\Ui\DataProvider\Filter\Applier as FilterApplier;
use Aheadworks\BlogSearch\Ui\DataProvider\ItemModifier\ModifierInterface;
use Aheadworks\BlogSearch\Ui\DataProvider\AbstractDataProvider;

/**
 * Post data provider
 */
class PostDataProvider extends AbstractDataProvider
{
    /**
     * @var FulltextCollection
     */
    protected $collection;

    /**
     * PostDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param FulltextCollectionFactory $fulltextCollectionFactory
     * @param FilterApplier $filterApplier
     * @param array $addFilterStrategyList
     * @param array $meta
     * @param array $data
     * @param ModifierInterface|null $itemModifier
     * @throws \Magento\Framework\Exception\ConfigurationMismatchException
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        FulltextCollectionFactory $fulltextCollectionFactory,
        FilterApplier $filterApplier,
        array $addFilterStrategyList = [],
        array $meta = [],
        array $data = [],
        ModifierInterface $itemModifier = null
    ) {
        $this->collection = $fulltextCollectionFactory->create();

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $filterApplier,
            $addFilterStrategyList,
            $meta,
            $data,
            $itemModifier
        );
    }
}
