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
namespace Aheadworks\Blog\Controller\Block;

use Aheadworks\Blog\Block\Widget\RecentPost;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\App\Action\Context;
use Aheadworks\Blog\Model\Serialize\SerializeInterface;
use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;

/**
 * Class Render
 *
 * @package Aheadworks\Blog\Controller\Block
 */
class Render extends \Magento\Framework\App\Action\Action
{
    /**
     * @var InlineInterface
     */
    private $translateInline;

    /**
     * @var SerializeInterface
     */
    private $serializer;

    /**
     * @param Context $context
     * @param InlineInterface $translateInline
     * @param SerializeFactory $serializeFactory
     */
    public function __construct(
        Context $context,
        InlineInterface $translateInline,
        SerializeFactory $serializeFactory
    ) {
        parent::__construct($context);
        $this->translateInline = $translateInline;
        $this->serializer = $serializeFactory->create();
    }

    /**
     * Returns block content depends on ajax request
     *
     * @return \Magento\Framework\Controller\Result\Redirect|void
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setRefererOrBaseUrl();
        }

        $blocks = $this->getRequest()->getParam('blocks');
        $data = $this->getBlocks($blocks);

        $this->translateInline->processResponseBody($data);
        $this->getResponse()->appendBody($this->serializer->serialize($data));
    }

    /**
     * Get blocks from layout
     *
     * @param string $blocks
     * @return string[]
     */
    private function getBlocks($blocks)
    {
        if (!$blocks) {
            return [];
        }
        $blocks = $this->serializer->unserialize($blocks);

        $data = [];
        $layout = $this->_view->getLayout();
        foreach ($blocks as $blockDataEncode) {
            try {
                $blockData = $this->serializer->unserialize(base64_decode($blockDataEncode));
                $blockName = '';
                if (isset($blockData['name'])) {
                    $blockName = $blockData['name'];
                    unset($blockData['name']);
                }

                if (strpos($blockName, RecentPost::WIDGET_NAME_PREFIX, 0) === false) {
                } else {
                    $blockInstance = $layout->createBlock(
                        RecentPost::class,
                        '',
                        ['data' => $blockData]
                    );
                    if (is_object($blockInstance)) {
                        $blockInstance->setNameInLayout($blockName);
                        $data[$blockDataEncode] = $blockInstance->toHtml();
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return $data;
    }
}
