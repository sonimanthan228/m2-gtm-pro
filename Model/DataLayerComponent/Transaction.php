<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Request\Http;
use Magento\Checkout\Model\Session;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;

/**
 * Class ProductView
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
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
   public function getComponentData($eventData) {
       $data = [];
       if (!$this->config->isTransactionTrackingEnabled()
           && $this->request->getFullActionName() == 'checkout_onepage_success' &&
           !$this->checkoutSession->getQuoteId() && $this->checkoutSession->getLastOrderId()) {
           /** @var Order $order */
           $order = $this->checkoutSession->getLastRealOrder();

           $data['ecommerce'] = [
               'currencyCode' => $order->getOrderCurrencyCode(),
               'purchase' => [
                   'actionField' => [
                       'id' => $order->getIncrementId(),
                       'affiliation' => $this->config->getTransactionAffiliation(),
                       'revenue' => round($order->getBaseGrandTotal(),2),
                       'shipping' => round($order->getBaseShippingAmount(),2),
                       'tax' => round($order->getBaseTaxAmount(),2),
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
                   'variant' => $this->getVariant($item->getProduct())
               ];
           }
       }

       return $data;
   }
}
