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
class AddToCart extends ComponentAbstract
{
    const EVENT_NAME = 'add_to_cart';
    
    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        $data = json_decode($this->checkoutSession->getGtmProProductAddToCartData());
        if (!is_array($data)) {
            $data = [];
        }

        $product = $item->getProduct();
        $qtyChange = (float)$item->getQty() - (float)$item->getOrigData('qty');
        $data[] = array_merge($this->getProductStructure($product, false), [
            'variant'  => $this->getVariant($item),
            'quantity' => $qtyChange,
            'price'    => (float)$item->getPriceInclTax() ?? $item->getPrice(),
            'discount' => (float)$item->getDiscountAmount(),
        ]);
        $this->checkoutSession->setGtmProProductAddToCartData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        $products = json_decode($this->checkoutSession->getGtmProProductAddToCartData(), true);
        if (is_array($products)) {
            $data['ecommerce'] = [
                'currency' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'value'    => $this->calculateValue($products),
                'items'    => $products,
            ];

            $this->cleanSessionGtmProductAddToCartData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProductAddToCartData()
    {
        $this->checkoutSession->setGtmProProductAddToCartData(false);
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
