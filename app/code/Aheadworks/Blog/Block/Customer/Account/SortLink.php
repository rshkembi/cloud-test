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

namespace Aheadworks\Blog\Block\Customer\Account;

use Aheadworks\Blog\Model\Config;
use Magento\Customer\Block\Account\SortLink as CustomerAccountSortLinkBlock;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Template\Context;

class SortLink extends CustomerAccountSortLinkBlock
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        private readonly Config $config,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $defaultPath,
            $data
        );
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isNeedToDisplayBlock()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Check if need to display block
     *
     * @return bool
     */
    private function isNeedToDisplayBlock(): bool
    {
        return $this->config->isBlogEnabled() &&
            ($this->config->isCommentsEnabled() && $this->config->getCommentType() === 'built-in');
    }
}
