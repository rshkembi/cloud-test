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
namespace Aheadworks\Blog\Model\Export\Collection\Filter;

use Aheadworks\Blog\Model\ResourceModel\AbstractCollection;

/**
 * Interface ProcessorInterface
 */
interface ProcessorInterface
{
    /**
     * The process of applying filters to a collection
     *
     * @param AbstractCollection $collection
     * @param string $columnName
     * @param array|string $value
     * @param null|string $type
     * @return void
     */
    public function process(AbstractCollection $collection, $columnName, $value, $type = null);
}