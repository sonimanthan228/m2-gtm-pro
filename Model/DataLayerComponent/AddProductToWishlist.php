<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;


/**
 * Class AddProductToWishlist
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
 */
class AddProductToWishlist extends ComponentAbstract implements DataLayerComponentInterface
{

    const EVENT_NAME = 'add-to-wishlist';

    /**
     * @param Item $item
     */
    public function processProduct(Product $product)
    {
        $data = json_decode($this->session->getGtmEeProductAddToWishlistData());
        if (!is_array($data)) {
            $data = [];
        }

        $data[] = [
            'name' => $product->getName(),
            'id' => $product->getId(),
            'price' => $this->formatPrice($product->getFinalPrice()),
            'brand' => $this->getBrand($product),
            'category' => $this->getCategoryName($product),
            'quantity' => $product->getQty()
        ];
        $this->checkoutSession->setGtmEeProductAddToWishlistData(json_encode($data));
    }

    public function getComponentData($eventData) {
        $data = [];
        $products = json_decode($this->checkoutSession->getGtmEeProductAddToWishlistData());
        if (is_array($products)) {
           $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'add' => [
                   'products' => $products
               ]
           ];

           $this->cleanSessionGtmEeProductAddToWishlistData();
        }

       return $data;
   }

    /**
     * @return void
     */
    protected function cleanSessionGtmEeProductAddToWishlistData()
    {
        $this->checkoutSession->setGtmEeProductAddToWishlistData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
