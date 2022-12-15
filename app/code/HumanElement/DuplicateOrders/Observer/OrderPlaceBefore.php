<?php
namespace HumanElement\DuplicateOrders\Observer;

use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;

class OrderPlaceBefore implements ObserverInterface
{
    protected $_responseFactory;
    protected $_url;

    public function __construct(
        ResponseFactory $responseFactory,
        UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/order-process-notification-logs.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $logger->info('Duplicate Order checker started.');

        /**
         *
         * NOTE THIS IS JUST A SAMPLE PULLED FROM THE ISSUE THAT IS IN GITHUB:
         * https://github.com/magento/magento2/issues/13952
         *
         * We can adjust the $endTime to be more than 15 seconds or try to account for orders placed at the
         * same time as well
         *
         * Cleanup instances of object manager
         *
         * Validate with other senior devs if there is a better way to achieve this
         *
         * (Mike, Steven, Carlos, Chris Nanninga)
         *
         */

        $event = $observer->getEvent();

        $order = $observer->getEvent()->getOrder();
        $quoteID = $order->getQuoteId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('sales_order');
        $selectedTime = date('Y-m-d h:i:s');
        $endTime = strtotime("-600 seconds", strtotime($selectedTime));
        $last15Sec = date('Y-m-d h:i:s', $endTime);

        $sql = "SELECT * FROM `".$tableName."` WHERE `quote_id` = ".$quoteID." and `created_at` >= '$last15Sec'";
        $logger->info('Query: '.$sql);
        if($result = $connection->fetchRow($sql)){
            $logger->info('Duplicate order found! Quote: '.$quoteID);
            $from = "dublicateOrders@stovercompany.com";
            $nameFrom = "Duplicate Orders";
            $nameTo = "Human Element Team";
            $body = ' A new duplicate order tentative was detected. Quote: '.$quoteID. '. The order process was stopped and the customer was redirected to home page.';
            $to = [
                'rshkembi@human-element.com',
                'pbriscoe@human-element.com',
                'pbriscoe@human-element.com',
                'rkroening@human-element.com',
                'blorenz@human-element.com',
                'ryanstover@stovercompany.com',
                'kgardner@human-element.com'
            ];
            $email = new \Zend_Mail();
            $email->setSubject("Stover Duplicate Order Detected!");
            $email->setBodyText($body);
            $email->setFrom($from, $nameFrom);
            $email->addTo($to, $nameTo);
            $email->send();
            $logger->info('Notification was triggered!');
            $customerBeforeAuthUrl = $this->_url->getUrl('checkout/cart/index');
            $this->_responseFactory->create()->setRedirect($customerBeforeAuthUrl)->sendResponse();
            exit;
        }

    }
}
