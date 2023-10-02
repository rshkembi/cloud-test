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
namespace Aheadworks\BlogSearch\Block;

use Magento\Framework\View\Element\Template;
use Aheadworks\BlogSearch\Model\SearchAllowedChecker;

/**
 * Class SearchField
 */
class SearchField extends Template
{
    /**
     * @var SearchAllowedChecker
     */
    private $searchAllowedChecker;

    /**
     * SearchField constructor.
     * @param Template\Context $context
     * @param SearchAllowedChecker $searchAllowedChecker
     * @param array $data
     */
    public function __construct
    (
        Template\Context $context,
        SearchAllowedChecker $searchAllowedChecker,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->searchAllowedChecker = $searchAllowedChecker;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!$this->searchAllowedChecker->execute()) {
            return '';
        }
        return parent::_toHtml();
    }
}
