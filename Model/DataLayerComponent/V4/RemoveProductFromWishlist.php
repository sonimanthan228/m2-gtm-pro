<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Catalog\Model\Product;

/**
 * Class RemoveProductFromWishlist
 */
class RemoveProductFromWishlist extends ComponentAbstract
{
    const EVENT_NAME = 'remove_from_wishlist';
    
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
                'items' => $products
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
}
