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

namespace Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface;
use Aheadworks\Blog\Model\Email\Queue\Item;
use Aheadworks\Blog\Model\ResourceModel\AbstractCollection;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item as ItemResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = ItemInterface::ENTITY_ID;

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Item::class,
            ItemResourceModel::class
        );
    }
}
