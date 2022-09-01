<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Quote\Model\Quote\Item;

/**
 * Class AddToCart
 */
class RemoveFromCart extends ComponentAbstract
{
    const EVENT_NAME = 'remove_from_cart';
    
    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        $data = json_decode($this->checkoutSession->getGtmProProductRemoveFromCartData(), true);
        if (!is_array($data)) {
            $data = [];
        }

        $product = $item->getProduct();
        $qtyChange = $item->isDeleted() ? (float)$item->getQty() : (float)$item->getOrigData('qty') - (float)$item->getQty();
        $data[] = array_merge($this->getProductStructure($product, false), [
            'variant'  => $this->getVariant($item),
            'quantity' => $qtyChange,
            'price'    => (float)$item->getPriceInclTax(),
            'discount' => (float)$item->getDiscountAmount(),
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
        $products = json_decode($this->checkoutSession->getGtmProProductRemoveFromCartData(), true);
        if (is_array($products)) {
            $data['ecommerce'] = [
                'currency' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'value'    => $this->calculateValue($products),
                'items'    => $products,
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

    protected function calculateValue(array $products): float
    {
        return array_reduce(
            $products,
            function($carry, $product) {
                return $carry + (((float)$product['price'] - (float)$product['discount']) * (float)$product['quantity']);
            },
            0
        );
    }
}
