<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class ProductView
 */
class Transaction extends ComponentAbstract
{
    const EVENT_NAME = 'transaction';

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * @param $eventData
     * @return array
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isModuleEnabled()
            && $this->config->isTransactionTrackingEnabled()
            && $this->request->getFullActionName() == 'checkout_onepage_success'
            && $this->checkoutSession->getLastRealOrderId()
        ) {
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
                /** @var \Magento\Sales\Model\Order\Item $item */
                $product = $item->getProduct();
                $data['ecommerce']['purchase']['products'][] = array_merge($this->getProductStructure($product, false), [
                   'quantity' => (int)$item->getQtyOrdered(),
                    /** @TODO variant has to be fixed, It's not working entirely, because Order\Item is passed to the fuction that is expecting Qoute\Item */
                   //'variant' => $this->getVariant($item)
                ]);
            }
        }

        return $data;
    }
}
