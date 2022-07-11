<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class AddProductToWishlist
 */
class AddProductToWishlist extends ComponentAbstract
{
    const EVENT_NAME = 'add_to_wishlist';

    /**
     * @param Product $product
     * @throws LocalizedException
     */
    public function processProduct(Product $product)
    {
        $data = json_decode($this->session->getGtmProProductAddToWishlistData());
        if (!is_array($data)) {
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
        $products = json_decode($this->session->getGtmProProductAddToWishlistData());
        if (is_array($products)) {
            $data['ecommerce'] = [
               'items' => $products
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
}
