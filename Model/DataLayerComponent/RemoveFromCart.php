<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;


/**
 * Class RemoveFromCart
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
 */
class RemoveFromCart extends ComponentAbstract implements DataLayerComponentInterface
{

    const EVENT_NAME = 'remove-from-cart';

    public function processProduct(Item $item)
    {
        $data = json_decode($this->checkoutSession->getGtmEeProductRemoveFromCartData());
        if (!is_array($data)) {
            $data = [];
        }

        $product = $item->getProduct();

        $data[] = [
            'name' => $product->getName(),
            'id' => $product->getId(),
            'price' => $this->formatPrice($product->getFinalPrice()),
            'brand' => $this->getBrand($product),
            'category' => $this->getCategoryName($product),
            'variant' => $this->getVariant($item),
            'quantity' => $item->getQty()
        ];
        $this->checkoutSession->setGtmEeProductRemoveFromCartData(json_encode($data));
    }

   public function getComponentData($eventData) {
       $data = [];
       $products = json_decode($this->checkoutSession->getGtmEeProductRemoveFromCartData());
       if (is_array($products)) {
           $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
               ]
           ];
           $this->cleanSessionGtmProductRemoveFromCartData();
       }

       return $data;
   }

    /**
     * @return void
     */
   protected function cleanSessionGtmProductRemoveFromCartData()
   {
       $this->checkoutSession->setGtmEeProductRemoveFromCartData(false);
   }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
