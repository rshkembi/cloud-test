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

use Magento\CheckoutAgreements\Api\Data\AgreementInterface;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsRepositoryInterface;
use Magento\CheckoutAgreements\Model\AgreementModeOptions;
use Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory as CheckoutAgreementsCollectionFactory;
use Magento\CheckoutAgreements\Model\ResourceModel\Agreement\Collection as CheckoutAgreementsCollection;

class Resolver
{
    /**
     * @param CheckoutAgreementsRepositoryInterface $checkoutAgreementsRepository
     * @param CheckoutAgreementsCollectionFactory $checkoutAgreementsCollectionFactory
     */
    public function __construct(
        private readonly CheckoutAgreementsRepositoryInterface $checkoutAgreementsRepository,
        private readonly CheckoutAgreementsCollectionFactory $checkoutAgreementsCollectionFactory
    ) {
    }

    /**
     * Retrieve array of agreements for comments
     *
     * @param int $storeId
     * @param bool $onlyRequiredAgreementsFlag
     * @return AgreementInterface[]
     */
    public function getAgreementsForComments(int $storeId, bool $onlyRequiredAgreementsFlag = false): array
    {
        /** @var CheckoutAgreementsCollection $checkoutAgreementsCollection*/
        $checkoutAgreementsCollection = $this->checkoutAgreementsCollectionFactory->create();

        $checkoutAgreementsCollection
            ->addStoreFilter($storeId)
            ->addFieldToFilter(AgreementInterface::IS_ACTIVE, true);

        if ($onlyRequiredAgreementsFlag) {
            $checkoutAgreementsCollection->addFieldToFilter(
                AgreementInterface::MODE,
                AgreementModeOptions::MODE_MANUAL
            );
        }

        $checkoutAgreements = [];
        /** @var AgreementInterface $item */
        foreach ($checkoutAgreementsCollection->getItems() as $item) {
            $checkoutAgreements[] = $this->checkoutAgreementsRepository->get($item->getAgreementId());
        }

        return $checkoutAgreements;
    }
}
