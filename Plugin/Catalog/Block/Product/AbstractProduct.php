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
use Magento\Framework\Serialize\Serializer\Json;
use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent\ProductImpression;
use Hatimeria\GtmPro\Model\DataLayerComponent\ProductClick;

/**
 * Class AbstractProduct
 */
class AbstractProduct
{
    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var ProductImpression
     */
    protected $productImpression;

    /**
     * @var ProductClick
     */
    protected $productClick;

    /**
     * AbstractProduct constructor.
     * @param Json $jsonSerializer
     * @param Config $config
     * @param ProductImpression $productImpression
     * @param ProductClick $productClick
     */
    public function __construct(
        Json $jsonSerializer,
        Config $config,
        ProductImpression $productImpression,
        ProductClick $productClick
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->config = $config;
        $this->productImpression = $productImpression;
        $this->productClick = $productClick;
    }

    /**
     * @param CatalogAbstractProduct $subject
     * @param $result
     * @param Product $product
     * @return string
     */
    public function afterGetProductDetailsHtml($subject, $result, $product)
    {
        if ($this->config->isModuleEnabled() && $this->config->isProductImpressionTrackingEnabled()) {
            $impressionData = $this->productImpression->getData(['object' => $product]);
            $result .= "<div class='product-impression-data' data-impression='"
                . $this->jsonSerializer->serialize($impressionData) . "'></div>";
        }

        if ($this->config->isModuleEnabled() && $this->config->isProductClickTrackingEnabled()) {
            $clickData = $this->productClick->getData(['object' => $product]);
            $result .= "<div class='product-click-data' data-click='"
                . $this->jsonSerializer->serialize($clickData)
                . "' data-url='" . $product->getProductUrl() . "'></div>";
        }

        return $result;
    }
}
