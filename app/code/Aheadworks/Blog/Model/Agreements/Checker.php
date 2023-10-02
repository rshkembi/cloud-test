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

namespace Aheadworks\Blog\Model\Agreements;

use Aheadworks\Blog\Model\BuiltinConfig;
use Aheadworks\Blog\Model\Source\Comment\Agreements\DisplayMode as AgreementsDisplayMode;

class Checker
{
    /**
     * @param BuiltinConfig $config
     */
    public function __construct(
        private readonly BuiltinConfig $config
    ) {
    }

    /**
     * Check if agreements are enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function areAgreementsEnabled(?int $storeId): bool
    {
        return $this->config->enableTermsAndConditions($storeId);
    }

    /**
     * Check if need to show agreements for guests
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isNeedToShowForGuests(?int $storeId): bool
    {
        $displayMode = $this->config->getAgreementsDisplayMode($storeId);
        return $displayMode === AgreementsDisplayMode::GUESTS_ONLY
            || $displayMode === AgreementsDisplayMode::EVERYONE;
    }

    /**
     * Check if need to show agreements for customers
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isNeedToShowForCustomers(?int $storeId): bool
    {
        $displayMode = $this->config->getAgreementsDisplayMode($storeId);
        return $displayMode === AgreementsDisplayMode::EVERYONE;
    }
}
