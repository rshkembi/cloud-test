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
namespace Aheadworks\BlogSearch\Model\Adapter\FieldMapper;

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Class PostFieldMapper
 */
class PostFieldMapper implements FieldMapperInterface
{
    /**#@+
     * Text flags for Elasticsearch field types
     */
    const ES_DATA_TYPE_TEXT = 'text';
    const ES_DATA_TYPE_KEYWORD = 'keyword';
    const ES_DATA_TYPE_DOUBLE = 'double';
    const ES_DATA_TYPE_INT = 'integer';
    const ES_DATA_TYPE_DATE = 'date';
    /**#@-*/

    /**
     * @inheritdoc
     */
    public function getFieldName($attributeCode, $context = [])
    {
       return $attributeCode;
    }

    /**
     * @inheritdoc
     */
    public function getAllAttributesTypes($context = [])
    {
        return [
            PostInterface::TITLE => [
                'type' => self::ES_DATA_TYPE_TEXT
            ],
            PostInterface::CONTENT => [
                'type' => self::ES_DATA_TYPE_TEXT
            ],
            PostInterface::AUTHOR => [
                'type' => self::ES_DATA_TYPE_TEXT
            ],
            PostInterface::TAG_NAMES => [
                'type' => self::ES_DATA_TYPE_TEXT
            ],
            PostInterface::META_TITLE => [
                'type' => self::ES_DATA_TYPE_TEXT,
            ],
            PostInterface::META_KEYWORDS => [
                'type' => self::ES_DATA_TYPE_TEXT,
            ],
            PostInterface::META_DESCRIPTION => [
                'type' => self::ES_DATA_TYPE_TEXT,
            ],
        ];
    }
}