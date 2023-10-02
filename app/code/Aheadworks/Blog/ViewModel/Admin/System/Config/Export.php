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
namespace Aheadworks\Blog\ViewModel\Admin\System\Config;

use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class Export
 */
class Export implements ArgumentInterface
{
    /**
     * @var \Aheadworks\Blog\Model\Serialize\SerializeInterface
     */
    private $serializer;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Import constructor.
     * @param SerializeFactory $serializer
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        SerializeFactory $serializer,
        RequestInterface $request,
        UrlInterface $urlBuilder
    ) {
        $this->serializer = $serializer->create();
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get script options
     *
     * @return bool|string
     */
    public function getScriptOptions()
    {
        $params = [
            'action' => $this->getExportUrl(),
            'actionExport' => $this->urlBuilder->getUrl('aw_blog_admin/export/export'),
        ];

        return $this->serializer->serialize($params);
    }

    /**
     * Retrieve export url
     *
     * @return string
     */
    public function getExportUrl()
    {
        return $this->urlBuilder->getUrl(
            'aw_blog_admin/export/getFilter',
            [
                '_current' => true,
                '_secure' => $this->request->isSecure()
            ]
        );
    }
}