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

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\View\Element\Template\Context;

/**
 * ShareThis integration block
 *
 * @method PostInterface getPost()
 * @method $this setPost(PostInterface $post)
 *
 * @package Aheadworks\Blog\Block
 */
class Sharethis extends \Magento\Framework\View\Element\Template
{
    /**
     * ShareThis script url
     */
    const EMBED_SCRIPT_URL = 'http://w.sharethis.com/button/buttons.js';

    /**
     * Secure ShareThis script url
     */
    const EMBED_SCRIPT_URL_SECURE = 'https://ws.sharethis.com/button/buttons.js';

    /**
     * {@inheritdoc}
     */
    protected $_template = 'sharethis.phtml';

    /**
     * @var Url
     */
    private $url;

    /**
     * @param Context $context
     * @param Url $url
     * @param array $data
     */
    public function __construct(
        Context $context,
        Url $url,
        array $data = []
    ) {
        $this->url = $url;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getShareUrl()
    {
        return $this->url->getPostUrl($this->getPost());
    }

    /**
     * @return string
     */
    public function getShareText()
    {
        return $this->getPost()->getTitle();
    }

    /**
     * @return string
     */
    public function getEmbedScriptUrl()
    {
        return $this->getRequest()->isSecure() ?
            self::EMBED_SCRIPT_URL_SECURE :
            self::EMBED_SCRIPT_URL;
    }
}
