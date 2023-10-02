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
declare(strict_types=1);

namespace Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow;

use Aheadworks\Blog\Model\ResourceModel\AbstractCollection;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface
    as EmailSubscriberDataRowInterface;
use Aheadworks\Blog\Model\Email\Subscriber\DataRow
    as EmailSubscriberDataRow;
use Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow
    as EmailSubscriberDataRowResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = EmailSubscriberDataRowInterface::ENTITY_ID;

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            EmailSubscriberDataRow::class,
            EmailSubscriberDataRowResourceModel::class
        );
    }
}
