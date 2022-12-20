<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Plugin\Catalog\Block\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Block\Product\AbstractProduct as CatalogAbstractProduct;
use Magento\Catalog\Block\Product\ListProduct as ListProductBlock;
use Hatimeria\GtmPro\Block\Catalog\Product\ProductList\ItemDataLayerData;

/**
 * Class AbstractProduct
 */
class AbstractProduct
{
    /**
     * @param CatalogAbstractProduct|ListProductBlock $subject
     * @param $result
     * @param Product $product
     * @return string
     */
    public function afterGetProductDetailsHtml($subject, $result, $product)
    {
        /** @var ItemDataLayerData $itemDataLayer */
        $itemDataLayer = $subject->getLayout()->getBlockSingleton(ItemDataLayerData::class);
        $dataLayer = $itemDataLayer->setProduct($product)
            ->setTemplate('Hatimeria_GtmPro::catalog/product/productlist/itemdatalayerdata.phtml')
            ->toHtml();

        return empty($result) || empty($dataLayer) || strstr($result, $dataLayer) ? $result : $result . $dataLayer;
    }
}
