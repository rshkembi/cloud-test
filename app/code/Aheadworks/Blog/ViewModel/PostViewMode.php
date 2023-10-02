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

namespace Aheadworks\Blog\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\RequestInterface;

class PostViewMode implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ArgumentInterface
     */
    private $request;

    /**
     * @param Config $config
     * @param RequestInterface $request
     */
    public function __construct(
        Config $config,
        RequestInterface $request
    ) {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Get grid view column count
     *
     * @return int
     */
    public function getGridViewColumnCount(): int
    {
        return $this->config->getGridViewColumnCount();
    }

    /**
     * Get grid view column count
     *
     * @return string
     */
    public function getDefaultViewMode(): string
    {
        return $this->config->getDefaultPostView();
    }

    /**
     * Get current view mode
     *
     * @return null|string
     */
    public function getCurrentViewMode(): ?string
    {
        return $this->request->getParam('post_list_mode') ?: $this->getDefaultViewMode();
    }
}