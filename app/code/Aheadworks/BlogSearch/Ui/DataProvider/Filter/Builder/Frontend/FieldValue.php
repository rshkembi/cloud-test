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

use Aheadworks\BlogSearch\Ui\DataProvider\Filter\BuilderInterface as FilterBuilderInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Exception\ConfigurationMismatchException;

/**
 * Class FieldValue
 */
class FieldValue implements FilterBuilderInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $fieldValue;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * FieldValue constructor.
     * @param FilterBuilder $filterBuilder
     * @param string $fieldName
     * @param string $fieldValue
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        string $fieldName = '',
        string $fieldValue = ''
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->fieldName = $fieldName;
        $this->fieldValue = $fieldValue;
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
        if (empty($this->fieldValue)) {
            throw new ConfigurationMismatchException(
                __('Specify field value to add filter by')
            );
        }

        $filter = $this->filterBuilder
            ->setConditionType('eq')
            ->setField($this->fieldName)
            ->setValue($this->fieldValue)
            ->create();

        return $filter;
    }
}
