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

namespace Aheadworks\Blog\Model\Url\Builder;

use Magento\Framework\UrlInterface;

class Frontend
{
    /**
     * Request parameter name for security code on email notification settings page
     */
    public const SECURITY_CODE_REQUEST_PARAMETER_NAME = 'code';

    /**
     * @param UrlInterface $frontendUrlBuilder
     */
    public function __construct(
        private readonly UrlInterface $frontendUrlBuilder,
    ) {
    }

    /**
     * Retrieve frontend url to view and change email notification settings of subscriber
     *
     * @param string $securityCode
     * @param array $additionalParams
     * @param int|null $storeId
     * @return string
     */
    public function getSubscriberEmailNotificationSettingsUrl(
        string $securityCode,
        array $additionalParams = [],
        ?int $storeId = null
    ): string {
        $params = $additionalParams;
        $params[self::SECURITY_CODE_REQUEST_PARAMETER_NAME] = $securityCode;
        $params['_scope_to_url'] = true;
        return $this->getUrl(
            'aw_blog/email_notification_settings/view',
            $storeId,
            $params
        );
    }

    /**
     * Get frontend url
     *
     * @param string $routePath
     * @param string|int|null $scope
     * @param array $params
     * @return string
     */
    public function getUrl(string $routePath, $scope, array $params): string
    {
        return $this->frontendUrlBuilder
            ->setScope($scope)
            ->getUrl(
                $routePath,
                $params
            );
    }
}
