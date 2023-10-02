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
namespace Aheadworks\Blog\Model\Data\Processor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Resolver\DuplicateEntityUrlKeySuffix;
use Aheadworks\Blog\Model\Resolver\UrlKeyUniqueVerifiableStores as UrlKeyUniqueVerifiableStoresResolver;

/**
 * class FieldSuffixForDuplicatedPost
 */
class FieldSuffixForDuplicatedPost implements ProcessorInterface
{
    /**
     * @var DuplicateEntityUrlKeySuffix
     */
    private $duplicateEntityUrlKeySuffix;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var UrlKeyUniqueVerifiableStoresResolver
     */
    private $urlKeyUniqueVerifiableStoresResolver;

    /**
     * @param DuplicateEntityUrlKeySuffix $duplicateEntityUrlKeySuffix
     * @param UrlKeyUniqueVerifiableStoresResolver $urlKeyUniqueVerifiableStoresResolver
     * @param array $fields
     */
    public function __construct(
        DuplicateEntityUrlKeySuffix $duplicateEntityUrlKeySuffix,
        UrlKeyUniqueVerifiableStoresResolver $urlKeyUniqueVerifiableStoresResolver,
        $fields = []
    ) {
        $this->duplicateEntityUrlKeySuffix = $duplicateEntityUrlKeySuffix;
        $this->urlKeyUniqueVerifiableStoresResolver = $urlKeyUniqueVerifiableStoresResolver;
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        $saveAction = isset($data['action']) ? $data['action'] : null;
        $urlKey = isset($data[PostInterface::URL_KEY]) ? $data[PostInterface::URL_KEY] : null;

        $storeIds = isset($data[PostInterface::STORE_IDS]) ? $data[PostInterface::STORE_IDS] : [];
        $storeIds = $this->urlKeyUniqueVerifiableStoresResolver->getStoresToVerify($storeIds);

        if ($saveAction === 'save_as_draft_and_duplicate' && $urlKey && $this->fields) {
            $suffix = $this->duplicateEntityUrlKeySuffix->getUrlKeySuffix($urlKey, $storeIds);
            foreach ($this->fields as $field) {
                $fieldValue = isset($data[$field]) ? $data[$field] : null;
                if ($fieldValue) {
                    $data[$field] = $fieldValue . $suffix;
                }
            }
        }

        return $data;
    }
}
