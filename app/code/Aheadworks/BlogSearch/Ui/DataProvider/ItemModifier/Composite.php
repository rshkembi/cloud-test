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
namespace Aheadworks\BlogSearch\Ui\DataProvider\ItemModifier;

/**
 * Class Composite
 */
class Composite implements ModifierInterface
{
    /**
     * @var ModifierInterface[]
     */
    private $modifiers;

    /**
     * Composite constructor.
     * @param array $modifiers
     */
    public function __construct(
        $modifiers = []
    ) {
        $this->modifiers = $modifiers;
    }

    /**
     * @inheritdoc
     */
    public function modify($item)
    {
        foreach ($this->modifiers as $modifier) {
            $item = $modifier->modify($item);
        }

        return $item;
    }
}
