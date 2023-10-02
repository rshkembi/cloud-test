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
namespace Aheadworks\BlogGraphQl\Model\Resolver\FilterArgument\EntityAttributesForAst;

/**
 * Interface DataPreparerInterface
 * @package Aheadworks\BlogGraphQl\Model\Resolver\FilterArgument\EntityAttributesForAst
 */
interface DataPreparerInterface
{
    /**
     * Retrieve prepared data
     *
     * @param $fields
     * @return array
     */
    public function getData($fields);
}