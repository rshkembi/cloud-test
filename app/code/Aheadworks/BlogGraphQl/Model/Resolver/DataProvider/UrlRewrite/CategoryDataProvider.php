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
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model\Resolver\DataProvider\UrlRewrite;

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\UrlRewriteGraphQl\Model\DataProvider\EntityDataProviderInterface;

class CategoryDataProvider implements EntityDataProviderInterface
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly DataObjectProcessor $dataObjectProcessor
    ) {
    }

    /**
     * Get catalog tree data
     *
     * @param string $entity_type
     * @param int $id
     * @param ResolveInfo|null $info
     * @param int|null $storeId
     * @return array
     * @throws LocalizedException
     */
    public function getData(
        string $entity_type,
        int $id,
        ResolveInfo $info = null,
        int $storeId = null
    ): array {
        $categoryId = (int)$id;
        $category = $this->categoryRepository->get($categoryId);
        $result = $this->dataObjectProcessor->buildOutputDataArray(
            $category,
            CategoryInterface::class
        );
        $result['type_id'] = $entity_type;

        return $result;
    }
}
