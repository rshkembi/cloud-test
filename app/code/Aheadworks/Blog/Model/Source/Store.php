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

namespace Aheadworks\Blog\Model\Source;

use Magento\Framework\Escaper;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;

class Store extends StoreOptions
{
    /**
     * Value for 'All Store Views' option
     */
    public const ALL_STORE_VIEWS_VALUE = '0';

    /**
     * @param SystemStore $systemStore
     * @param Escaper $escaper
     * @param bool $isNeedToAddAllStoreViewsOption
     */
    public function __construct(
        SystemStore $systemStore,
        Escaper $escaper,
        private readonly bool $isNeedToAddAllStoreViewsOption = false
    ) {
        parent::__construct($systemStore, $escaper);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $this->currentOptions = [];

        if ($this->isNeedToAddAllStoreViewsOption) {
            $this->currentOptions['All Store Views']['label'] = __('All Store Views');
            $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS_VALUE;
        }

        $this->generateCurrentOptions();

        return array_values($this->currentOptions);
    }
}
