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
namespace Aheadworks\Blog\Model\Rule\Product\Collection\Preparer\Filter;

use Aheadworks\Blog\Model\Rule\Product\Collection\PreparerInterface;
use Magento\Catalog\Model\Product\Visibility as SourceVisibility;

/**
 * Class Visibility
 */
class Visibility implements PreparerInterface
{
    /**
     * @var SourceVisibility
     */
    private $sourceVisibility;

    /**
     * Visibility constructor.
     * @param SourceVisibility $sourceVisibility
     */
    public function __construct(
        SourceVisibility $sourceVisibility
    ) {
        $this->sourceVisibility = $sourceVisibility;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($collection, $parameterList)
    {
        $isFilterVisibilityEnabled = $parameterList[PreparerInterface::IS_FILTER_VISIBILITY_ENABLED] ?? null;
        if ($isFilterVisibilityEnabled) {
            $collection->setVisibility($this->sourceVisibility->getVisibleInSiteIds());
        }

        return $collection;
    }
}
