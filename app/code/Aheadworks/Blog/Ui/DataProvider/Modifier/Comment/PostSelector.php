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

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class PostSelector implements ModifierInterface
{
    /**
     * @param ArrayManager $arrayManager
     * @param AuthorizationInterface $authorization
     * @param string $aclResourceId
     * @param string $pathToButtonComponent
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly AuthorizationInterface $authorization,
        private readonly string $aclResourceId = '',
        private readonly string $pathToButtonComponent = ''
    ) {
    }

    /**
     * Modify data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        return $data;
    }

    /**
     * Modify meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        if ($this->isPostListingAvailable()) {
            return $meta;
        }

        if ($this->getPathToButtonComponent()) {
            $meta = $this->arrayManager->set(
                $this->getPathToButtonComponent(),
                $meta,
                $this->getButtonComponentAdditionalMeta()
            );
        }

        return $meta;
    }

    /**
     * Check if product listing is available
     *
     * @return bool
     */
    private function isPostListingAvailable(): bool
    {
        return $this->authorization->isAllowed($this->getAclResource());
    }

    /**
     * Retrieve acl resource for the product listing
     *
     * @return string
     */
    private function getAclResource(): string
    {
        return $this->aclResourceId;
    }

    /**
     * Retrieve path to the form button component, which is responsible for modal with product listing
     *
     * @return string
     */
    private function getPathToButtonComponent(): string
    {
        return $this->pathToButtonComponent;
    }

    /**
     * Retrieve button component additional metadata
     *
     * @return array
     */
    private function getButtonComponentAdditionalMeta(): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'disabled' => true,
                        'elementTmpl' => 'Aheadworks_Blog/ui/form/element/button/wrapper',
                        'innerElementTmpl' => 'ui/form/element/button',
                        'afterElementTmpl' => 'Aheadworks_Blog/ui/form/element/button/notice',
                        'noticeLabel' => __('Sorry, you need permissions to view this content.'),
                    ],
                ],
            ],
        ];
    }
}
