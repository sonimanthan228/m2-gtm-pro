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

/**
 * Class RemoveFromCart
 */
class RemoveFromCart extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'remove-from-cart';
    
    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        $data = json_decode($this->checkoutSession->getGtmProProductRemoveFromCartData());
        if (!is_array($data)) {
            $data = [];
        }

        $product = $item->getProduct();

        $data[] = [
            'name' => $this->processName($product->getName()),
            'id' => $product->getId(),
            'price' => $this->formatPrice($product->getFinalPrice()),
            'brand' => $this->getBrand($product),
            'category' => $this->getCategoryName($product),
            'variant' => $this->getVariant($item),
            'quantity' => $item->getQty()
        ];
        $this->checkoutSession->setGtmProProductRemoveFromCartData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        $products = json_decode($this->checkoutSession->getGtmProProductRemoveFromCartData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
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

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
