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
namespace Aheadworks\Blog\Model\Rule\Product\Collection;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\Exception\LocalizedException;

interface PreparerInterface
{
    /**#@+
     * Constants defined for the keys of $parameterList array
     */
    const CONDITIONS_KEY = 'conditions';
    const WEBSITE_IDS_KEY = 'website_ids';
    const PRODUCTS_FILTER_KEY = 'products_filter';
    const IS_FILTER_VISIBILITY_ENABLED = 'is_filter_visibility_enabled';
    /**#@-*/

    /**
     * Prepare product collection to be validated by product rule
     *
     * @param ProductCollection $collection
     * @param array $parameterList
     * @return ProductCollection
     * @throws LocalizedException
     */
    public function prepare($collection, $parameterList);
}
