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
namespace Aheadworks\BlogSearch\Block\Pager;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\BlogSearch\Ui\DataProvider\AbstractDataProvider;
use Magento\Theme\Block\Html\Pager;
use Magento\Framework\Data\Collection;

/**
 * Class Wrapper
 */
class Wrapper extends Template
{
    /**
     * Native pager child block alias
     */
    const PAGER_BLOCK_ALIAS = 'pager';

    /**
     * @var AbstractDataProvider
     */
    private $dataProvider;

    /**
     * Wrapper constructor.
     * @param Context $context
     * @param AbstractDataProvider $dataProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        AbstractDataProvider $dataProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataProvider = $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->applyPagingSettings();
        return $this;
    }

    /**
     * Apply paging settings to Data Provider
     *
     * @return $this
     */
    private function applyPagingSettings()
    {
        /** @var Pager|bool $pager */
        $pager = $this->getChildBlock(self::PAGER_BLOCK_ALIAS);
        if ($pager) {
            $collection = $this->dataProvider->getCollection();
            if ($collection instanceof Collection) {
                $pager->setCollection($collection);
            }
        }
        return $this;
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml(self::PAGER_BLOCK_ALIAS);
    }
}
