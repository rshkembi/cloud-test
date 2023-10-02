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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Aheadworks\Blog\Model\Layout\JsLayoutTemplateConverter;
use Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\ElementNameUpdater\ElementNameResolver;

class ElementNameUpdater implements LayoutProcessorInterface
{
    /**
     * @param JsLayoutTemplateConverter $jsLayoutTemplateConverter
     * @param ElementNameResolver $elementNameResolver
     * @param array $fieldListToUpdateName
     */
    public function __construct(
        private readonly JsLayoutTemplateConverter $jsLayoutTemplateConverter,
        private readonly ElementNameResolver $elementNameResolver,
        private readonly array $fieldListToUpdateName = []
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $jsLayoutTemplate = $relatedObjectList[self::JS_LAYOUT_TEMPLATE_KEY] ?? [];
        $commentId = $relatedObjectList[self::COMMENT_ID_KEY] ?? null;

        if (isset($commentId)) {
            $elementNamePostfix = $this->elementNameResolver->getElementNamePostfix($commentId);

            $jsLayout = $this->jsLayoutTemplateConverter->convertToJsLayoutTemplate(
                $jsLayoutTemplate,
                $elementNamePostfix,
                $this->fieldListToUpdateName
            );
        }

        return $jsLayout;
    }
}
