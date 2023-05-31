<?php
declare(strict_types=1);

namespace HumanElement\OrderColdPackDetail\Plugin\Order;

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
     * @param OrderManagementInterface $subject
     * @param OrderInterface           $order
     *
     * @return OrderInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforePlace(
        OrderManagementInterface $subject,
        OrderInterface $order
    ): array {

        $hasColdPack = false;
        foreach($order->getItems() as $item){
            if($item->getSku() != self::COLD_PACK_SKU)
                continue;
            $hasColdPack = true;
        }
        if($hasColdPack){
            $order->setData('customer_note',self::COLD_PACK_MESSAGE);
        }
        return [$order];
    }
}
