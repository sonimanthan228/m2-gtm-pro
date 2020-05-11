<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class ProductView
 */
class Transaction extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'transaction';

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }

    /**
     * @param $eventData
     * @return array
     */
    public function getComponentData($eventData)
    {
        $data = [];
        if (!$this->config->isTransactionTrackingEnabled()
           && $this->request->getFullActionName() == 'checkout_onepage_success' &&
           !$this->checkoutSession->getQuoteId() && $this->checkoutSession->getLastOrderId()) {
            /** @var Order $order */
            $order = $this->checkoutSession->getLastRealOrder();
            $quote = $this->quoteFactory->create()->load($order->getQuoteId());
            
            $data['ecommerce'] = [
               'currencyCode' => $order->getOrderCurrencyCode(),
               'purchase' => [
                   'actionField' => [
                       'id' => $order->getIncrementId(),
                       'affiliation' => $this->config->getTransactionAffiliation(),
                       'revenue' => round($order->getBaseGrandTotal(), 2),
                       'shipping' => round($order->getBaseShippingAmount(), 2),
                       'tax' => round($order->getBaseTaxAmount(), 2),
                       'coupon' => $order->getCouponCode(),
                   ],
                   'products' => []
               ]
            ];

            foreach ($order->getAllVisibleItems() as $item) {
                /** @var Order\Item $item */
                $data['ecommerce']['purchase']['products'][] = [
                   'name' => $item->getName(),
                   'id' => $item->getSku(),
                   'category' => $this->getCategoryName($item->getProduct()),
                   'price' => $this->formatPrice($item->getBasePrice()),
                   'quantity' => (int)$item->getQtyOrdered(),
                   'variant' => $this->getVariant($quote->getItemById($item->getQuoteItemId()))
                ];
            }
        }

        return $data;
    }
}
