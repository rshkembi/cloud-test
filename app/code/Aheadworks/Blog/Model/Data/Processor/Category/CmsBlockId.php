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
namespace Aheadworks\Blog\Model\Data\Processor\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;

/**
 * Class CmsBlockId
 */
class CmsBlockId implements ProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process($data)
    {
        if (isset($data[CategoryInterface::CMS_BLOCK_ID])) {
            $data[CategoryInterface::CMS_BLOCK_ID] = $data[CategoryInterface::CMS_BLOCK_ID] ?: null;
        }

        return $data;
    }
}