<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class RemoveProductFromCompare
 */
class RemoveProductFromCompare extends ComponentAbstract implements DataLayerComponentInterface
{

    const EVENT_NAME = 'remove-from-compare';

    /**
     * @param $productData
     */
    public function processData($productData)
    {
        $data = json_decode($this->session->getGtmEeProductRemoveFromCompareData());
        if (!is_array($data)) {
            $data = [];
        }

        $data[] = [
            'id' => $productData['product_id'],
        ];
        $this->session->setGtmEeProductRemoveFromCompareData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        $products = json_decode($this->session->getGtmEeProductRemoveFromCompareData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
               ]
            ];

            $this->cleanSessionGtmEeProductRemoveFromCompareData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmEeProductRemoveFromCompareData()
    {
        $this->session->setGtmEeProductRemoveFromCompareData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
