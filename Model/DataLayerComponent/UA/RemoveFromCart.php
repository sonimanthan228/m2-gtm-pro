<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Magento\Quote\Model\Quote\Item;

/**
 * Class RemoveFromCart
 */
class RemoveFromCart extends ComponentAbstract
{
    const EVENT_NAME = 'remove-from-cart';

    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        if ($data = $this->checkoutSession->getGtmProProductRemoveFromCartData()) {
           $data = json_decode($data);
        } else {
            $data = [];
        }

        $product = $item->getProduct();
        $data[] = array_merge($this->getProductStructure($product, false), [
            'variant' => $this->getVariant($item),
            'quantity' => $item->getQty()
        ]);
        $this->checkoutSession->setGtmProProductRemoveFromCartData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($products = $this->checkoutSession->getGtmProProductRemoveFromCartData()) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => json_decode($products)
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
        $this->checkoutSession->setGtmProProductRemoveFromCartData(false);
    }
}
