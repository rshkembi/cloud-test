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

namespace Aheadworks\Blog\Model\Captcha;

use Aheadworks\Blog\Model\Source\Captcha\DisplayMode as CaptchaDisplayModeSourceModel;
use Aheadworks\Blog\Api\Data\CommentInterface;

class Resolver
{
    /**
     * @var array
     */
    private array $displayModeMap = [
        'by_customer' => [
            'guest' => [
                CaptchaDisplayModeSourceModel::GUEST_COMMENT_SUBMIT,
                CaptchaDisplayModeSourceModel::GUEST_REPLY_COMMENT_SUBMIT
            ],
            'customer' => [
                CaptchaDisplayModeSourceModel::CUSTOMER_COMMENT_SUBMIT,
                CaptchaDisplayModeSourceModel::CUSTOMER_REPLY_COMMENT_SUBMIT
            ],
        ],
        'by_entity' => [
            CommentInterface::class => [
                CaptchaDisplayModeSourceModel::GUEST_COMMENT_SUBMIT,
                CaptchaDisplayModeSourceModel::CUSTOMER_COMMENT_SUBMIT
            ],
            'ReplyCommentInterface' => [
                CaptchaDisplayModeSourceModel::GUEST_REPLY_COMMENT_SUBMIT,
                CaptchaDisplayModeSourceModel::CUSTOMER_REPLY_COMMENT_SUBMIT
            ]
        ],
    ];

    /**
     * Retrieve list of allowed display modes according to the current type of customer
     *
     * @param bool $isCustomerLoggedIn
     * @return array
     */
    public function getAllowedDisplayModeListByCustomer(bool $isCustomerLoggedIn = false): array
    {
        return $isCustomerLoggedIn
            ? $this->displayModeMap['by_customer']['customer']
            : $this->displayModeMap['by_customer']['guest'];
    }

    /**
     * Retrieve list of allowed display modes according to the current type of entity
     *
     * @param string $entityInterfaceName
     * @return array
     */
    public function getAllowedDisplayModeListByEntity(string $entityInterfaceName): array
    {
        return $this->displayModeMap['by_entity'][$entityInterfaceName] ?? [];
    }
}
