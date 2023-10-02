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
namespace Aheadworks\Blog\Model\Indexer\Cache\Processor;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\EntityManager\EventManager;

/**
 * Class Cleaner
 */
class Cleaner
{
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var CacheInterface
     */
    private $appCache;

    /**
     * @param EventManager $eventManager
     * @param CacheInterface $appCache
     */
    public function __construct(
        EventManager $eventManager,
        CacheInterface $appCache
    ) {
        $this->eventManager = $eventManager;
        $this->appCache = $appCache;
    }

    /**
     * Clean cache with the instance of IdentityInterface
     *
     * @param IdentityInterface $identity
     * @return $this
     */
    public function execute($identity)
    {
        $this->eventManager->dispatch(
            'clean_cache_by_tags',
            [
                'object' => $identity
            ]
        );
        $this->appCache->clean(
            $identity->getIdentities()
        );
        return $this;
    }
}