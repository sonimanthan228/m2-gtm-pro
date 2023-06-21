<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class RemoveProductFromCompare
 */
class RemoveProductFromCompare extends ComponentAbstract
{
    const EVENT_NAME = 'remove-from-compare';

    /**
     * @param $productData
     */
    public function processProduct($productData)
    {
        $data = json_decode($this->session->getGtmProProductRemoveFromCompareData(), true);
        if (!is_array($data)) {
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
    public function getComponentData($eventData): ?array
    {
        $data = [];
        $products = json_decode($this->session->getGtmProProductRemoveFromCompareData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
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
}
