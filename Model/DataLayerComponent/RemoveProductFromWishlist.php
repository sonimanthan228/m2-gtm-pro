<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class RemoveProductFromWishlist
 */
class RemoveProductFromWishlist extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'remove-from-wishlist';

    /**
     * @param Product $product
     */
    public function processProduct(Product $product)
    {
        $data = json_decode($this->session->getGtmEeProductRemoveFromWishlistData());
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

        $this->session->setGtmEeProductRemoveFromWishlistData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        $products = json_decode($this->session->getGtmEeProductRemoveFromWishlistData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
               ]
            ];

            $this->cleanSessionGtmEeProductRemoveFromWishlistData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmEeProductRemoveFromWishlistData()
    {
        $this->session->setGtmEeProductRemoveFromWishlistData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
