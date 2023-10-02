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

namespace Aheadworks\Blog\Block\Adminhtml\Comment\Button\Edit;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Reply implements ButtonProviderInterface
{
    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly UrlInterface $urlBuilder,
    ) {
    }

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->request->getActionName() !== 'edit' || !$this->request->getParam('id')) {
            return $data;
        }
        $commentId = (int)$this->request->getParam('id');
        $data = [
            'label' => __('Reply'),
            'class' => 'reply',
            'on_click' =>
                sprintf("location.href = '%s';",
                    $this->urlBuilder->getUrl('*/*/reply', ['id' => $commentId])),
            'sort_order' => 40
        ];

        return $data;
    }
}
