<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class AddProductToCompare
 */
class AddProductToCompare extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'add-to-compare';

    /**
     * @param Product $product
     */
    public function processProduct(Product $product)
    {
        $data = json_decode($this->session->getGtmProProductAddToCompareData());
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
        $this->session->setGtmProProductAddToCompareData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        $products = json_decode($this->session->getGtmProProductAddToCompareData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'add' => [
                   'products' => $products
               ]
            ];

            $this->cleanSessionGtmProProductAddToCompareData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProProductAddToCompareData()
    {
        $this->session->setGtmProProductAddToCompareData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
