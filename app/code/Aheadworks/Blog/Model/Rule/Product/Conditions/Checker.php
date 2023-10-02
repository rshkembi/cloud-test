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
namespace Aheadworks\Blog\Model\Rule\Product\Conditions;

use Magento\CatalogRule\Model\Rule\Condition\Combine as CatalogRuleConditionCombine;

class Checker
{
    /**
     * Check if it is possible to apply conditions to the product collection for loading optimization
     *
     * @param CatalogRuleConditionCombine $conditions
     * @return bool
     */
    public function canApplyConditionsToProductCollection($conditions)
    {
        if (!$conditions || !$conditions->getConditions()) {
            return false;
        }

        return true;
    }
}
