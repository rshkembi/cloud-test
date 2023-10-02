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

namespace Aheadworks\Blog\Model\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;

class Resolver
{
    /**
     * @param CustomerSession $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly CustomerRepositoryInterface $customerRepository
    ) {
    }

    /**
     * Retrieve current customer
     *
     * @return CustomerInterface|null
     */
    public function getCurrentCustomer(): ?CustomerInterface
    {
        $currentCustomerId = (int)$this->customerSession->getCustomerId();

        return $this->getCustomerById($currentCustomerId);
    }

    /**
     * Retrieve customer by id
     *
     * @param int|null $customerId
     * @return CustomerInterface|null
     */
    public function getCustomerById(?int $customerId): ?CustomerInterface
    {
        try {
            $customer = $this->customerRepository->getById($customerId);
        } catch (LocalizedException $exception) {
            $customer = null;
        }

        return $customer;
    }

    /**
     * Retrieve default website id for specific customer id
     *
     * @param int $customerId
     * @return int|null
     */
    public function getDefaultWebsiteIdForCustomerId(int $customerId): ?int
    {
        $customer = $this->getCustomerById($customerId);

        return $customer ? $this->getDefaultWebsiteIdForCustomer($customer) : null;
    }

    /**
     * Retrieve customer by email
     *
     * @param string|null $customerEmail
     * @param int|null $websiteId
     * @return CustomerInterface|null
     */
    public function getCustomerByEmail(?string $customerEmail, ?int $websiteId = null): ?CustomerInterface
    {
        try {
            $customer = $this->customerRepository->get($customerEmail, $websiteId);
        } catch (LocalizedException $exception) {
            $customer = null;
        }

        return $customer;
    }

    /**
     * Retrieve default website id for specific customer
     *
     * @param CustomerInterface $customer
     * @return int
     */
    public function getDefaultWebsiteIdForCustomer(CustomerInterface $customer): int
    {
        return (int)$customer->getWebsiteId();
    }
}
