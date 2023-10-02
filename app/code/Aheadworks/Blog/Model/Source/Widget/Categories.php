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
namespace Aheadworks\Blog\Model\Source\Widget;

use Magento\Framework\Option\ArrayInterface;
use Aheadworks\Blog\Model\Source\Categories as CategoriesSource;

/**
 * Class Categories
 * @package Aheadworks\Blog\Model\Source\Widget
 */
class Categories extends CategoriesSource implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return parent::toOptionArray();
    }
}
