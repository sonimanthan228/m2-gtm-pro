<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductImpression
 */
class ProductClick extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'product-click';

    /**
     * @param $eventData
     * @return array|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        if (array_key_exists('object', $eventData)) {
            $product = $eventData['object'];
            $category = $this->getCategoryName($product);
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'detail' => [
                   'actionField' => [
                       'list' => $category
                   ],
                   'products' => [
                       'name' => $this->processName($product->getName()),
                       'id' => $product->getId(),
                       'price' => $this->formatPrice($product->getFinalPrice()),
                       'brand' => $this->getBrand($product),
                       'category' => $category
                   ]
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
