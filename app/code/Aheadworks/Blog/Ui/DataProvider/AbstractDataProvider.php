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
declare(strict_types=1);

namespace Aheadworks\Blog\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider as UiAbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface as ModifierPoolInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;
use Magento\Framework\Api\Filter;

abstract class AbstractDataProvider extends UiAbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ModifierPoolInterface $modifierPool
     * @param AddFilterToCollectionInterface[] $addFilterStrategyList
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        protected readonly ModifierPoolInterface $modifierPool,
        protected readonly array $addFilterStrategyList = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Add field filter to collection
     *
     * @param Filter $filter
     * @return void
     */
    public function addFilter(Filter $filter)
    {
        if (isset($this->addFilterStrategyList[$filter->getField()])) {
            $this->addFilterStrategyList[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [
                        $filter->getConditionType() => $filter->getValue()
                    ]
                );
        } else {
            parent::addFilter($filter);
        }
    }

    /**
     * Get data
     *
     * @return array
     * @throws LocalizedException
     */
    public function getData()
    {
        $data = parent::getData();
        $data['items'] = $this->prepareItemsData($data['items']);

        return $data;
    }

    /**
     * Return Meta
     *
     * @return array
     * @throws LocalizedException
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        foreach ($this->modifierPool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }

    /**
     * Modify items data using modifiers pool
     *
     * @param array $itemsData
     * @return array
     * @throws LocalizedException
     */
    protected function prepareItemsData(array $itemsData): array
    {
        foreach ($this->modifierPool->getModifiersInstances() as $modifier) {
            $itemsData = $modifier->modifyData($itemsData);
        }

        return $itemsData;
    }
}
