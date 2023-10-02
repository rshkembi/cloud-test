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
namespace Aheadworks\Blog\Model\Resolver;

use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * class UrlKeyUniqueVerifiableStores
 */
class UrlKeyUniqueVerifiableStores
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
     * Returns stores that must be checked for url key unique if we try to save entity to given stores
     *
     * @param array $storeIds
     * @return array
     */
    public function getStoresToVerify($storeIds)
    {
        if (in_array(Store::DEFAULT_STORE_ID, $storeIds)) {
            $allStores = array_keys($this->storeManager->getStores());
            $storeIds = array_merge($storeIds, $allStores);
        } else {
            $storeIds[] = Store::DEFAULT_STORE_ID;
        }

        return array_unique($storeIds);
    }
}
