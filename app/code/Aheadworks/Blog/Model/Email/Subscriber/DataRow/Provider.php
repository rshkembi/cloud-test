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

namespace Aheadworks\Blog\Model\Email\Subscriber\DataRow;

use Aheadworks\Blog\Api\EmailSubscriberDataRowRepositoryInterface;
use Aheadworks\Blog\Model\BuiltinConfig;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterfaceFactory;
use Aheadworks\Blog\Model\Customer\Resolver as CustomerResolver;

class Provider
{
    /**
     * @param EmailSubscriberDataRowRepositoryInterface $dataRowRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param BuiltinConfig $config
     * @param DataRowInterfaceFactory $dataRowFactory
     * @param CustomerResolver $customerResolver
     */
    public function __construct(
        private readonly EmailSubscriberDataRowRepositoryInterface $dataRowRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly BuiltinConfig $config,
        private readonly DataRowInterfaceFactory $dataRowFactory,
        private readonly CustomerResolver $customerResolver
    ) {
    }

    /**
     * Retrieve data row
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return DataRowInterface
     */
    public function getDataRow(
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId,
        string $emailQueueItemType
    ): DataRowInterface {
        $searchCriteria = $this->getSearchCriteria($customerId, $recipientEmail, $websiteId, $emailQueueItemType);

        $dataRowList = $this->dataRowRepository->getList($searchCriteria)->getItems();

        if (count($dataRowList)) {
            $dataRow = reset($dataRowList);
        } else {
            $dataRow = $this->getDefaultDataRow($searchCriteria);
        }

        return $dataRow;
    }

    /**
     * Retrieve search criteria to find corresponding data row
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return SearchCriteria
     */
    private function getSearchCriteria(
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId,
        string $emailQueueItemType
    ): SearchCriteria {
        $this->searchCriteriaBuilder
            ->addFilter(
                DataRowInterface::WEBSITE_ID,
                $websiteId,
                'eq'
            )->addFilter(
                DataRowInterface::NOTIFICATION_TYPE,
                $emailQueueItemType,
                'eq'
            )->setPageSize(
                1
            );

        $customer = $this->customerResolver->getCustomerById($customerId);
        $customerByEmail = $this->customerResolver->getCustomerByEmail($recipientEmail, $websiteId);
        $customerIdToSearch = $customer?->getId();
        $customerIdByEmail = $customerByEmail?->getId();
        $customerIdToSearch = empty($customerIdToSearch) ? $customerIdByEmail : $customerIdToSearch;

        if (empty($customerIdToSearch)) {
            $this->searchCriteriaBuilder
                ->addFilter(
                    DataRowInterface::CUSTOMER_EMAIL,
                    $recipientEmail,
                    'eq'
                );
        } else {
            $this->searchCriteriaBuilder
                ->addFilter(
                    DataRowInterface::CUSTOMER_ID,
                    $customerIdToSearch,
                    'eq'
                );
        }

        return $this->searchCriteriaBuilder->create();
    }

    /**
     * Retrieve new initialized default data row
     *
     * @param SearchCriteria $searchCriteria
     * @return DataRowInterface
     */
    private function getDefaultDataRow(SearchCriteria $searchCriteria): DataRowInterface
    {
        /** @var DataRowInterface $dataRow */
        $dataRow = $this->dataRowFactory->create(
            [
                'data' => $this->extractDataRowData($searchCriteria),
            ]
        );

        $customer = $this->customerResolver->getCustomerById($dataRow->getCustomerId());
        if ($customer) {
            $dataRow->setCustomerEmail($customer->getEmail());
        }

        $dataRow->setValue(
            $this->config->isRecipientSubscribedToNotificationByDefault(
                $dataRow->getWebsiteId()
            )
        );

        return $dataRow;
    }

    /**
     * Extract data row data from search criteria
     *
     * @param SearchCriteria $searchCriteria
     * @return array
     */
    private function extractDataRowData(SearchCriteria $searchCriteria): array
    {
        $dataRowData = [];
        foreach ($searchCriteria->getFilterGroups() as $group) {
            foreach ($group->getFilters() as $filter) {
                $dataRowData[$filter->getField()] = $filter->getValue();
            }
        }

        return $dataRowData;
    }
}
