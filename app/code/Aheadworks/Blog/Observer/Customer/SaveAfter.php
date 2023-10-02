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

namespace Aheadworks\Blog\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\Customer\Processor\Saving as CustomerSavingProcessor;

class SaveAfter implements ObserverInterface
{
    /**
     * @param CustomerSavingProcessor $customerSavingProcessor
     */
    public function __construct(
        private readonly CustomerSavingProcessor $customerSavingProcessor
    ) {
    }

    /**
     * Subscribe notification after create customer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var CustomerInterface $customer */
        $customer = $event->getData('customer');
        /** @var RequestInterface $request */
        $request = $event->getData('request');

        if ($customer instanceof CustomerInterface &&  $request instanceof RequestInterface) {
            $this->customerSavingProcessor->processSubscriberNotificationDataModification(
                $customer,
                $request
            );
        }
    }
}
