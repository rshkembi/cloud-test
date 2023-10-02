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
namespace Aheadworks\BlogGraphQl\Plugin\Model\Resolver;

use Aheadworks\Blog\Model\Config;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\CatalogUrlRewriteGraphQl\Model\Resolver\CategoryUrlSuffix;

/**
 * Class CategoryUrlSuffixPlugin
 * @package Aheadworks\BlogGraphQl\Plugin\Model\Resolver
 */
class CategoryUrlSuffixPlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param CategoryUrlSuffix $categoryUrlSuffix
     * @param string $result
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return string
     */
    public function afterResolve(
        CategoryUrlSuffix $categoryUrlSuffix,
        $result,
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ): string {
        if (is_array($value)
            && isset($value['url_path'])
            && $value['url_path'] == $this->config->getRouteToBlog()
        ) {
            $result = '';
        }
        return $result;
    }
}
