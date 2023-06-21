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
 * Class AddProductToWishlist
 */
class AddProductToWishlist extends ComponentAbstract
{
    const EVENT_NAME = 'add-to-wishlist';

    /**
     * @param Product $product
     */
    public function processProduct(Product $product)
    {
        if ($data = $this->session->getGtmProProductAddToWishlistData()) {
            $data = json_decode($data);
        } else {
            $data = [];
        }

        $data[] = $this->getProductStructure($product);
        $this->session->setGtmProProductAddToWishlistData(json_encode($data));
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($products = $this->session->getGtmProProductAddToWishlistData()) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'add' => [
                   'products' => json_decode($products)
               ]
            ];

            $this->cleanSessionGtmProProductAddToWishlistData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProProductAddToWishlistData()
    {
        $this->session->setGtmProProductAddToWishlistData(false);
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }
}
