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
use Aheadworks\Blog\Model\DateTime\Formatter;

/**
 * Class DateTimeFilter
 */
class DateTimeFilter implements ProcessorInterface
{
    /**
     * @var Formatter
     */
    private $dateFormatter;

    /**
     * DateTimeFilter constructor.
     * @param Formatter $dateFormatter
     */
    public function __construct(
        Formatter $dateFormatter
    ) {
        $this->dateFormatter = $dateFormatter;
    }

    /**
     * @inheritDoc
     */
    public function process(AbstractCollection $collection, $columnName, $value, $type = null)
    {
        if (is_array($value)) {
            $from = $value[0] ?? null;
            $to = $value[1] ?? null;

            if (!empty($to)) {
                $to = $this->dateFormatter->getDate($to, 23, 59, 59);
                $collection->addFieldToFilter($columnName, ['lteq' => $to]);
            }

            if (!empty($from)) {
                $from = $this->dateFormatter->getDate($from);
                $collection->addFieldToFilter($columnName, ['gteq' => $from]);
            }

            return;
        }
    }
}