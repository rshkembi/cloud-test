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
namespace Aheadworks\BlogGraphQl\Model\Resolver;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ArgumentHelper
 * @package Aheadworks\BlogGraphQl\Model\Resolver
 */
class ArgumentHelper
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
     * Return store id or default store id if not exists
     *
     * @param array $data
     * @return int
     */
    public function getStoreId($data)
    {
        return isset($data['storeId']) ? $data['storeId'] : $this->storeManager->getStore()->getId();
    }
}
