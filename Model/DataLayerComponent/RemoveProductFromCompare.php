<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
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
        if ($data = $this->session->getGtmProProductRemoveFromCompareData()) {
            $data = json_decode($data);
        } else {
            $data = [];
        }

        $data[] = [
            'id' => $productData['product_id'],
        ];
        $this->session->setGtmProProductRemoveFromCompareData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        if ($products = $this->session->getGtmProProductRemoveFromCompareData()) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => json_decode($products)
               ]
            ];

            $this->cleanSessionGtmProProductRemoveFromCompareData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProProductRemoveFromCompareData()
    {
        $this->session->setGtmProProductRemoveFromCompareData(false);
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
