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
namespace Aheadworks\Blog\Model\Rule\Product\Collection\Preparer\Conditions;

use Aheadworks\Blog\Model\Rule\Product\Collection\PreparerInterface;
use Magento\CatalogRule\Model\ResourceModel\Product\ConditionsToCollectionApplier;
use Magento\CatalogRule\Model\Rule\Condition\Combine as CatalogRuleConditionCombine;
use Aheadworks\Blog\Model\Rule\Product\Conditions\Checker as ProductRuleConditionsChecker;

class Applier implements PreparerInterface
{
    /**
     * @var ConditionsToCollectionApplier
     */
    private $conditionsToCollectionApplier;

    /**
     * @var ProductRuleConditionsChecker
     */
    private $productRuleConditionsChecker;

    /**
     * @param ConditionsToCollectionApplier $conditionsToCollectionApplier
     * @param ProductRuleConditionsChecker $productRuleConditionsChecker
     */
    public function __construct(
        ConditionsToCollectionApplier $conditionsToCollectionApplier,
        ProductRuleConditionsChecker $productRuleConditionsChecker
    ) {
        $this->conditionsToCollectionApplier = $conditionsToCollectionApplier;
        $this->productRuleConditionsChecker = $productRuleConditionsChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($collection, $parameterList)
    {
        $conditions = $parameterList[PreparerInterface::CONDITIONS_KEY] ?? null;
        if ($conditions instanceof CatalogRuleConditionCombine
            && $this->productRuleConditionsChecker->canApplyConditionsToProductCollection($conditions)
        ) {
            $collection = $this->conditionsToCollectionApplier->applyConditionsToCollection(
                $conditions,
                $collection
            );
        }
        return $collection;
    }
}
