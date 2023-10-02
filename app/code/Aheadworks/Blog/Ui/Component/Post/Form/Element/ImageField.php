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
namespace Aheadworks\Blog\Ui\Component\Post\Form\Element;

use Magento\Ui\Component\Form\Field;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;

/**
 * Class ImageField
 * @package Aheadworks\Blog\Ui\Component\Post\Form\Element
 */
class ImageField extends Field
{
    /**
     * @var FeaturedImageInfo
     */
    private $imageInfo;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param FeaturedImageInfo $imageInfo
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FeaturedImageInfo $imageInfo,
        array $components = [],
        array $data = []
    ) {
        $this->imageInfo = $imageInfo;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getData('config');
        if (!isset($config['mediaUrl'])) {
            $config['mediaUrl'] = $this->imageInfo->getMediaUrl();
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
