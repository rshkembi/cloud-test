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

namespace Aheadworks\Blog\Ui\DataProvider\Modifier\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class StoreId implements ModifierInterface
{
    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    /**
     * Modify meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
         return $meta;
    }

    /**
     * Modify store id
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        $storeId = $data[CommentInterface::STORE_ID] ?? null;

        $data[CommentInterface::STORE_ID . '_label'] =
            ($storeId !== null)
                ? $this->getStoreName((int)$storeId)
                : ''
        ;

        return $data;
    }

    /**
     * Retrieve store name by its id
     *
     * @param int $storeId
     * @return string
     */
    private function getStoreName(int $storeId): string
    {
        try {
            $store = $this->storeManager->getStore($storeId);
        } catch (LocalizedException $exception) {
            $store = null;
        }

        return isset($store) ? $store->getName() : '';
    }
}
