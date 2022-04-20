<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class RemoveProductFromWishlist
 */
class RemoveProductFromWishlist extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'remove-from-wishlist';

    /**
     * @param Product $product
     */
    public function processProduct(Product $product)
    {
        if ($data = $this->session->getGtmProProductRemoveFromWishlistData()) {
            $data = json_decode($data);
        } else {
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
    public function getComponentData($eventData)
    {
        $data = [];
        if ($products = $this->session->getGtmProProductRemoveFromWishlistData()) {
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'remove' => [
                   'products' => json_decode($products)
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
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
