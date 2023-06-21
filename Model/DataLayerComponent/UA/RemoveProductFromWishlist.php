<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Magento\Catalog\Model\Product;

/**
 * Class RemoveProductFromWishlist
 */
class RemoveProductFromWishlist extends ComponentAbstract
{
    const EVENT_NAME = 'remove-from-wishlist';

    /**
     * @param Product $product
     */
    public function processProduct(Product $product)
    {
        $data = json_decode($this->session->getGtmProProductRemoveFromWishlistData(), true);
        if (!is_array($data)) {
            $data = [];
        }

        $data[] = $this->getProductStructure($product);

        $this->session->setGtmProProductRemoveFromWishlistData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        $products = json_decode($this->session->getGtmProProductRemoveFromWishlistData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => $products
               ]
            ];

            $this->cleanSessionGtmProProductRemoveFromWishlistData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProProductRemoveFromWishlistData()
    {
        $this->session->setGtmProProductRemoveFromWishlistData(false);
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }
}
