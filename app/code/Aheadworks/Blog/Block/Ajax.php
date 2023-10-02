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
namespace Aheadworks\Blog\Block;

use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Serialize\SerializeInterface;
use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;

/**
 * Class Ajax
 *
 * @package Aheadworks\Blog\Block
 */
class Ajax extends \Magento\Framework\View\Element\Template
{
    /**
     * @var SerializeInterface
     */
    private $serializer;

    /**
     * @param Context $context
     * @param array $data
     * @param SerializeFactory $serializeFactory
     */
    public function __construct(
        Context $context,
        SerializeFactory $serializeFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializeFactory->create();
    }

    /**
     * Retrieve script options encoded to json
     *
     * @return string
     */
    public function getScriptOptions()
    {
        $params = [
            'url' => $this->getUrl(
                'aw_blog/block/render/',
                [
                    '_current' => true,
                    '_secure' => $this->templateContext->getRequest()->isSecure()
                ]
            )
        ];
        return $this->serializer->serialize($params);
    }
}
