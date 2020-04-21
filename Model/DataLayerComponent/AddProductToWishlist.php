<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class AddProductToWishlist
 */
class AddProductToWishlist extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'add-to-wishlist';

    /**
     * @param Product $product
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
        $this->session->setGtmEeProductAddToWishlistData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        $products = json_decode($this->session->getGtmEeProductAddToWishlistData());
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
        $this->session->setGtmEeProductAddToWishlistData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
