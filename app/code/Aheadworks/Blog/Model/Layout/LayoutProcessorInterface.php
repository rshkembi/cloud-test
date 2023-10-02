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

interface LayoutProcessorInterface
{
    public const POST_KEY = 'post';
    public const STORE_KEY = 'store';
    public const COMMENT_ID_KEY = 'comment_id';
    public const JS_LAYOUT_TEMPLATE_KEY = 'js_layout_template';
    public const CUSTOMER_KEY = 'customer';
    public const SECURITY_CODE = 'security_code';

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array;
}
