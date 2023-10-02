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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model\Resolver\DataProvider;

use Aheadworks\BlogGraphQl\Model\ObjectConverter;
use Magento\Framework\Api\SearchResultsInterface;

abstract class AbstractDataProvider implements DataProviderInterface
{
    /**
     * @param ObjectConverter $objectConverter
     */
    public function __construct(
        private readonly ObjectConverter $objectConverter
    ) {
    }

    /**
     * Use class reflection on given data interface to build output data array
     *
     * @param SearchResultsInterface $searchResult
     * @param string $objectType
     * @param array $args
     * @return void
     */
    protected function convertResultItemsToDataArray(
        SearchResultsInterface $searchResult,
        string $objectType,
        array $args
    ): void {
        $itemsAsArray = [];
        foreach ($searchResult->getItems() as $item) {
            $itemsAsArray[] = $this->objectConverter->convertToArray($item, $objectType, $args);
        }

        $searchResult->setItems($itemsAsArray);
    }

}

