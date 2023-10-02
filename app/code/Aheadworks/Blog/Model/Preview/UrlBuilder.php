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
namespace Aheadworks\Blog\Model\Preview;

use Magento\Framework\UrlInterface as FrameworkUrlInterface;
use Magento\Framework\App\Area;

/**
 * Class UrlBuilder
 * @package Aheadworks\Blog\Model\Preview
 */
class UrlBuilder
{
    /**
     * @var array
     */
    private $urlBuilders = [];

    /**
     * @param array $urlBuilders
     */
    public function __construct(
        array $urlBuilders = []
    ) {
        $this->urlBuilders = $urlBuilders;
    }

    /**
     * Get url
     *
     * @param string $routePath
     * @param string $scope
     * @param array $params
     * @param string $areaCode
     * @return string
     */
    public function getUrl($routePath, $scope, $params, $areaCode = Area::AREA_ADMINHTML)
    {
        $href = '';
        $urlBuilder = $this->getUrlBuilder($areaCode);

        if ($urlBuilder) {
            $urlBuilder->setScope($scope);
            $href = $urlBuilder->getUrl(
                $routePath,
                $params
            );
        }
        return $href;
    }

    /**
     * Get url builder object
     *
     * @param string $areaCode
     * @return FrameworkUrlInterface|null
     */
    private function getUrlBuilder($areaCode)
    {
        $urlBuilder = null;
        if (isset($this->urlBuilders[$areaCode])
            && $this->urlBuilders[$areaCode] instanceof FrameworkUrlInterface
        ) {
            $urlBuilder = $this->urlBuilders[$areaCode];
        }

        return $urlBuilder;
    }
}
