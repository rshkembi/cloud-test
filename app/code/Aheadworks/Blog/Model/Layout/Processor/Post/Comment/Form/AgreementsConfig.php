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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Form;

use Aheadworks\Blog\Model\Agreements\Checker as AgreementsChecker;
use Aheadworks\Blog\Model\Agreements\Resolver as AgreementsResolver;
use Magento\Framework\Escaper;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Model\AgreementModeOptions;

class AgreementsConfig
{
    /**
     * @param AgreementsChecker $agreementsChecker
     * @param AgreementsResolver $agreementsResolver
     * @param Escaper $escaper
     */
    public function __construct(
        private readonly AgreementsChecker $agreementsChecker,
        private readonly AgreementsResolver $agreementsResolver,
        private readonly Escaper $escaper
    ) {
    }

    /**
     * Retrieve config data array for comments agreements
     *
     * @param int|null $storeId
     * @return array
     */
    public function getConfigData(?int $storeId = null): array
    {
        $areAgreementsEnabled = $this->agreementsChecker->areAgreementsEnabled($storeId);
        return [
            'are_agreements_enabled' => $areAgreementsEnabled,
            'is_need_to_show_for_guests' => $this->agreementsChecker->isNeedToShowForGuests($storeId),
            'is_need_to_show_for_customers' => $this->agreementsChecker->isNeedToShowForCustomers($storeId),
            'agreements_data' => $areAgreementsEnabled ? $this->getCommentAgreementsData($storeId) : [],
        ];
    }

    /**
     * Retrieve review agreements data array
     *
     * @param int|null $storeId
     * @return array
     */
    protected function getCommentAgreementsData(?int $storeId): array
    {
        $agreementsData = [];
        if (!empty($storeId)) {
            $agreementsList = $this->agreementsResolver->getAgreementsForComments($storeId);
            /** @var AgreementInterface $agreement */
            foreach ($agreementsList as $agreement) {
                $agreementsData[] = [
                    'content' => $agreement->getIsHtml()
                        ? $agreement->getContent()
                        : nl2br($this->escaper->escapeHtml($agreement->getContent())),
                    'checkboxText' => $agreement->getCheckboxText(),
                    'isRequired' => ($agreement->getMode() == AgreementModeOptions::MODE_MANUAL),
                    'agreementId' => $agreement->getAgreementId()
                ];
            }
        }

        return $agreementsData;
    }
}
