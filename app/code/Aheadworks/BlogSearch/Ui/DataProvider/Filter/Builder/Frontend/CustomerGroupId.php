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
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\ConfigurationMismatchException;

/**
 * Class CustomerGroupId
 */
class CustomerGroupId implements FilterBuilderInterface
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
     * @var HttpContext
     */
    private $httpContext;

    /**
     * CustomerGroupId constructor.
     * @param FilterBuilder $filterBuilder
     * @param HttpContext $httpContext
     * @param string $fieldName
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        HttpContext $httpContext,
        string $fieldName = ''
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->httpContext = $httpContext;
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

        $currentCustomerGroupId = $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP);
        if ($currentCustomerGroupId !== null) {
            $filter = $this->filterBuilder
                ->setConditionType('eq')
                ->setField($this->fieldName)
                ->setValue($currentCustomerGroupId)
                ->create();
        }

        return $filter;
    }
}
