<?php
declare(strict_types=1);

namespace HumanElement\OrderColdPackDetail\Plugin\Order;

use Amasty\Extrafee\Model\ResourceModel\ExtrafeeOrder\Collection as ExtraFee;
use Magento\Sales\Api\OrderRepositoryInterface as Orders;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;

/**
 * Class CheckColdpack
 */
class CheckColdpack
{
    const COLD_PACK_MESSAGE = 'COLDPACK SELECTED';

    const COLD_PACK_SKU = 'CP';

    /**
     * @var ExtraFee
     */
    protected $extraFee;

    /**
     * @var Orders
     */
    protected $orderRepository;

    public function __construct(ExtraFee $extraFee, Orders $orderRepository)
    {
        $this->extraFee = $extraFee;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderManagementInterface $subject
     * @param OrderInterface $result
     * @param Data\OrderInterface $order
     * @return OrderInterface
     */
    public function afterPlace(OrderManagementInterface $subject, OrderInterface $result, OrderInterface $order): OrderInterface
    {
        $cpFee = $this->extraFee->addFilterByOrderId($order->getEntityId())->getFirstItem();
        if (($cpFee && $cpFee->getDataByKey('total_amount') > 0) ||
            $this->checkOrderItemsForColdPack($order)) {

            $order->setData('customer_note', self::COLD_PACK_MESSAGE);
            $this->orderRepository->save($order);
        }

        return $result;
    }

    /**
     * Reviews order items purchased and looks for the presence of a cold pack item
     * @param $order
     * @return bool
     */
    private function checkOrderItemsForColdPack($order)
    {
        foreach ($order->getItems() as $item) {
            if ($item->getSku() != self::COLD_PACK_SKU) {
                continue;
            }
            return true;
        }

        return false;
    }
}
