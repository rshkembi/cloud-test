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
namespace Aheadworks\Blog\Model\Cms;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\Block;

/**
 * Class CmsBlockProvider
 */
class CmsBlockProvider
{
    /**
     * @var BlockFactory
     */
    private $cmsBlockFactory;

    /**
     * CmsBlockProvider constructor.
     * @param BlockFactory $cmsBlockFactory
     */
    public function __construct(
        BlockFactory $cmsBlockFactory
    ) {
        $this->cmsBlockFactory = $cmsBlockFactory;
    }

    /**
     * Retrieves CMS block by id
     *
     * @param int $cmsBlockId
     * @param int $storeId
     * @return Block|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCmsBlockById($cmsBlockId, $storeId)
    {
        if ($cmsBlockId) {
            $cmsBlock = $this->cmsBlockFactory->create()
                ->setStoreId($storeId)
                ->load($cmsBlockId);
        }

        return $cmsBlock ?? null;
    }
}