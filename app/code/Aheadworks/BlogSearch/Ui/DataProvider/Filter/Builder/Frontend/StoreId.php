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
namespace Aheadworks\BlogSearch\Ui\DataProvider\Filter\Builder\Frontend;

use Aheadworks\BlogSearch\Model\Store\Resolver as StoreResolver;
use Aheadworks\BlogSearch\Ui\DataProvider\Filter\BuilderInterface as FilterBuilderInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Exception\ConfigurationMismatchException;

/**
 * Class StoreId
 */
class StoreId implements FilterBuilderInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var StoreResolver
     */
    private $storeResolver;

    /**
     * StoreId constructor.
     * @param FilterBuilder $filterBuilder
     * @param StoreResolver $storeResolver
     * @param string $fieldName
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        StoreResolver $storeResolver,
        string $fieldName = ''
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->storeResolver = $storeResolver;
        $this->fieldName = $fieldName;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $filter = null;
        if (empty($this->fieldName)) {
            throw new ConfigurationMismatchException(
                __('Specify field name to add filter by')
            );
        }

        $currentStore = $this->storeResolver->getCurrentStore();
        if ($currentStore) {
            $filter = $this->filterBuilder
                ->setConditionType('eq')
                ->setField($this->fieldName)
                ->setValue($currentStore->getId())
                ->create();
        }

        return $filter;
    }
}
