<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Hatimeria\GtmPro\Model\DataLayerComponent\AbstractComponent;
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
     */
    public function getProductStructure(Product $product, bool $withQuantity = true): array
    {
        $structureKey = $product->getId() . '_' . (int)$withQuantity;
        if (!isset($this->productsStructure[$structureKey])) {
            $structure = [
                'name' => $this->getName($product),
                'id' => $product->getId(),
                'price' => $this->formatPrice($product->getFinalPrice()),
                'brand' => $this->getBrand($product),
                'category' => $this->getCategoryName($product)
            ];

            if ($withQuantity) {
                $structure['quantity'] = $product->getQty();
            }

            $this->productsStructure[$structureKey] = $structure;
        }

        return $this->productsStructure[$structureKey];
    }
}
