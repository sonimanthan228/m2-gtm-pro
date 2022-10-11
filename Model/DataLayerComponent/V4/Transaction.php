<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Order;

/**
 * Class ProductView
 */
class Transaction extends ComponentAbstract
{
    const EVENT_NAME = 'purchase';

    /**
     * @param $eventData
     * @return array
     * @throws LocalizedException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isModuleEnabled()
            && $this->config->isTransactionTrackingEnabled()
            && $this->request->getFullActionName() == 'checkout_onepage_success'
            && $this->checkoutSession->getLastRealOrderId()
        ) {
            $order = $this->checkoutSession->getLastRealOrder();
            $orderValue = (float)$order->getGrandTotal() - (float)$order->getShippingInclTax();

            $data['ecommerce'] = [
                'currency'       => $order->getOrderCurrencyCode(),
                'transaction_id' => $order->getIncrementId(),
                'value'          => $this->formatPrice($orderValue),
                'tax'            => $this->formatPrice($order->getTaxAmount()),
                'shipping'       => $this->formatPrice($order->getShippingAmount()),
                'coupon'         => $order->getCouponCode(),
                'items'          => $this->getOrderItems($order),
            ];
        }

        return $data;
    }

    /**
     * @throws LocalizedException
     */
    protected function getOrderItems(Order $order): array
    {
        $data = [];
        foreach($order->getAllVisibleItems() as $item) {
            $data[] = $this->getCartItemStructure($item);
        }

        return $data;
    }
}
