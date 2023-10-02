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
namespace Aheadworks\Blog\Model\Rule\Product\Collection\Preparer\ValidatedAttributes;

use Aheadworks\Blog\Model\Rule\Product\Collection\PreparerInterface;
use Magento\CatalogRule\Model\Rule\Condition\Combine as CatalogRuleConditionCombine;

class Collector implements PreparerInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepare($collection, $parameterList)
    {
        $conditions = $parameterList[PreparerInterface::CONDITIONS_KEY] ?? null;
        if ($conditions instanceof CatalogRuleConditionCombine) {
            $conditions->collectValidatedAttributes($collection);
        }
        return $collection;
    }
}
