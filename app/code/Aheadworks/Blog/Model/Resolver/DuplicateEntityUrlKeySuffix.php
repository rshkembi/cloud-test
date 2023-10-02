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

use Aheadworks\Blog\Model\ResourceModel\UrlKey\Collector as UrlKeyCollector;

/**
 * class DuplicateEntityUrlKeySuffix
 */
class DuplicateEntityUrlKeySuffix
{
    /**
     * @var UrlKeyCollector
     */
    private $urlKeyCollector;

    /**
     * @param UrlKeyCollector $urlKeyCollector
     */
    public function __construct(
        UrlKeyCollector $urlKeyCollector
    ) {
        $this->urlKeyCollector = $urlKeyCollector;
    }

    /**
     * Returns url key suffix for duplicated entity
     *
     * @param array $storeIds
     * @param string $originalEntityUrlKey
     * @returns string
     */
    public function getUrlKeySuffix(string $originalEntityUrlKey, array $storeIds = [])
    {
        $existingUrlKeys = $this->urlKeyCollector->getBlogUrlKeys($storeIds);

        $suffixNumber = 1;

        while (in_array($originalEntityUrlKey . $this->generateSuffixByNumber($suffixNumber), $existingUrlKeys)) {
            $suffixNumber ++;
        }

        return $this->generateSuffixByNumber($suffixNumber);
    }

    /**
     * Generates suffix by number
     *
     * @param int $suffixNumber
     * @return string
     */
    private function generateSuffixByNumber($suffixNumber)
    {
        return '-' . $suffixNumber;
    }
}
