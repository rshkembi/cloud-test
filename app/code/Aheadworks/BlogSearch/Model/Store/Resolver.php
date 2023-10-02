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
namespace Aheadworks\BlogSearch\Model\Store;

use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Resolver
 */
class Resolver
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Resolver constructor.
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve current store
     *
     * @return StoreInterface|null
     */
    public function getCurrentStore()
    {
        try {
            $currentStore = $this->storeManager->getStore();
        } catch (LocalizedException $exception) {
            $currentStore = null;
        }
        return $currentStore;
    }
}
