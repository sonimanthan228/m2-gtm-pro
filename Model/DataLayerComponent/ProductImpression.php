<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductImpression
 * @package Hatimeria\GtmEe\Model\DataLayerComponent
 */
class ProductImpression extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'product-impression';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
   public function getComponentData($eventData) {
       $data = [];
       if (array_key_exists('object', $eventData)) {
           $product = $eventData['object'];
           $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'impressions' => [
                    'name' => strip_tags($product->getName()),
                    'id' => $product->getId(),
                    'price' => $this->formatPrice($product->getFinalPrice()),
                   'brand' => $this->getBrand($product),
                    'category' => $this->getCategoryName($product)
//                    'position' => '' getiing on the front
                ]
           ];
       }

       return $data;
   }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
