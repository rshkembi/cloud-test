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

namespace Aheadworks\Blog\Model\Data;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface;

interface OperationInterface
{
    /**
     * Perform operation over entity data
     *
     * @param array $entityData
     * @return ItemInterface|CommentInterface|bool
     * @throws LocalizedException
     */
    public function execute(array $entityData);
}
