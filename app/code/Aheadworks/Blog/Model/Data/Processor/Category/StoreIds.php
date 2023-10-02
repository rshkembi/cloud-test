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

use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;

/**
 * Class StoreIds
 *
 * @package Aheadworks\Blog\Model\Data\Processor\Category
 */
class StoreIds implements ProcessorInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        if ($this->storeManager->hasSingleStore()) {
            $data[CategoryInterface::STORE_IDS] = [$this->storeManager->getStore(true)->getId()];
        }
        return $data;
    }
}
