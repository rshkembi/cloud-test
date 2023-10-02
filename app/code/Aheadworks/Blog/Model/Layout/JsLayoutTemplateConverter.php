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

namespace Aheadworks\Blog\Model\Layout;

use Magento\Framework\Stdlib\ArrayManager;

class JsLayoutTemplateConverter
{
    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private readonly ArrayManager $arrayManager
    ) {
    }

    /**
     * Convert layout template to standard layout by adding postfix to the list of elements
     *
     * @param array $jsLayoutTemplate
     * @param string $elementNamePostfix
     * @param array $fieldListToUpdateName
     * @return array
     */
    public function convertToJsLayoutTemplate(
        array $jsLayoutTemplate,
        string $elementNamePostfix,
        array $fieldListToUpdateName
    ): array {
        $jsLayout = array_merge_recursive([], $jsLayoutTemplate);

        foreach ($fieldListToUpdateName as $fieldPath) {
            $currentValue = $this->arrayManager->get($fieldPath, $jsLayout);
            if (is_string($currentValue)) {
                $updatedValue = $currentValue . $elementNamePostfix;
                $jsLayout = $this->arrayManager->set($fieldPath, $jsLayout, $updatedValue);
            } elseif(is_array($currentValue)) {
                $updatedPath = $fieldPath . $elementNamePostfix;
                $jsLayout = $this->arrayManager->set($updatedPath, $jsLayout, $currentValue);
                $jsLayout = $this->arrayManager->remove($fieldPath, $jsLayout);
            }
        }

        return $jsLayout;
    }
}
