<?php

namespace HumanElement\DuplicateOrders\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;

class OrderPlaceBefore implements ObserverInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ResponseFactory $responseFactory
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ResponseFactory $responseFactory,
        UrlInterface $url,
        LoggerInterface $logger,
        Session $checkoutSession

    ) {
        $this->orderRepository = $orderRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quoteID = $order->getQuoteId();
        $selectedTime = date('Y-m-d h:i:s');
        $endTime = strtotime("-600 seconds", strtotime($selectedTime));
        $last600Sec = date('Y-m-d h:i:s', $endTime);

        $filterEq = $this->filterBuilder
            ->setField("quote_id")
            ->setConditionType('eq')
            ->setValue($quoteID)
            ->create();
        $filterGteq = $this->filterBuilder
            ->setField("created_at")
            ->setConditionType('gteq')
            ->setValue($last600Sec)
            ->create();
        $this->searchCriteriaBuilder->addFilters([$filterEq]);
        $this->searchCriteriaBuilder->addFilters([$filterGteq]);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $orderRepository = $this->orderRepository->getList($searchCriteria);

        if($orderRepository->getTotalCount()){
            $this->logger->warning('Duplicate order found! Quote: '. $quoteID);
            $this->checkoutSession->setLastSuccessQuoteId($quoteID);
            $this->checkoutSession->setLastQuoteId($quoteID);
            foreach ($orderRepository as $order) {
                $this->checkoutSession->setLastOrderId($order->getId());
            }
            exit();
        }

    }
}
