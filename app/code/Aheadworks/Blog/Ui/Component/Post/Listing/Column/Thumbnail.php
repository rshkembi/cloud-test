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
namespace Aheadworks\Blog\Ui\Component\Post\Listing\Column;

use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

/**
 * Class Thumbnail
 * @package Aheadworks\Blog\Ui\Component\Post\Listing\Column
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var FeaturedImageInfo
     */
    private $imageHandler;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param FeaturedImageInfo $imageHandler
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        FeaturedImageInfo $imageHandler,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->imageHandler = $imageHandler;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['featured_image_file']) {
                    $imgUrl = $this->imageHandler->getImageUrl($item['featured_image_file']);
                    $item[$fieldName . '_src'] = $item[$fieldName . '_orig_src'] = $imgUrl;
                    $id = $item['id'];
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                        'aw_blog_admin/post/edit',
                        ['id' => $id]
                    );
                }
            }
        }
        return $dataSource;
    }
}
