<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

/**
 * Class AddToCart
 */
class AddToCart extends ComponentAbstract
{
    const EVENT_NAME = 'add-to-cart';

    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        if ($data = $this->checkoutSession->getGtmProProductAddToCartData()) {
            $data = json_decode($data);
        } else {
            $data = [];
        }

        $product = $item->getProduct();
        $data[] = array_merge($this->getProductStructure($product, false), [
            'variant' => $this->getVariant($item),
            'quantity' => $item->getQty()
        ]);
        $this->checkoutSession->setGtmProProductAddToCartData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws NoSuchEntityException|LocalizedException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($products = $this->checkoutSession->getGtmProProductAddToCartData()) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'add' => [
                   'actionField' => [
                       'list' => '',
                       'position' => ''
                   ],
                   'products' => json_decode($products)
               ]
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

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }
}
