<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Hatimeria\GtmPro\Model\DataLayerComponent\AbstractComponent;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\Product;

/**
 * Class ComponentAbstract
 */
abstract class ComponentAbstract extends AbstractComponent
{
    /**
     * @param Product $product
     * @param bool $withQuantity
     * @return array
     * @throws LocalizedException
     */
    public function getProductStructure(Product $product, bool $withQuantity = true): array
    {
        $structureKey = $product->getId() . '_' . (int)$withQuantity;
        if (!isset($this->productsStructure[$structureKey])) {
            $structure = [
                'item_id'    => $product->getId(),
                'item_name'  => $this->getName($product),
                'currency'   => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'price'      => $this->formatPrice($product->getFinalPrice()),
                'item_brand' => $this->getBrand($product),
            ];
            $number = '';
            foreach($this->getCategories($product) as $categoryName) {
                $structure['item_category' . $number] = $categoryName;
                $number = $number === '' ? 2 : $number + 1;
            }

            if ($withQuantity) {
                $structure['quantity'] = $product->getQty() ?: 1;
            }

            $this->productsStructure[$structureKey] = $structure;
        }

        return $this->productsStructure[$structureKey];
    }
}
