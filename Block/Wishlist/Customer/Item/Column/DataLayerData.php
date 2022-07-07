<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Wishlist\Customer\Item\Column;

use Hatimeria\GtmPro\Model\DataLayerComponent;
use Magento\Wishlist\Block\Customer\Wishlist\Item\Column;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\View\ConfigInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product;
use Hatimeria\GtmPro\Model\Config as GtmConfig;

/**
 * Class DataLayerData
 */
class DataLayerData extends Column
{
    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @var GtmConfig
     */
    protected $gtmConfig;

    /**
     * @var DataLayerComponent
     */
    protected $productImpression;

    /**
     * @var DataLayerComponent
     */
    protected $productClick;

    /**
     * DataLayerData constructor.
     * @param $context
     * @param Context $httpContext
     * @param GtmConfig $gtmConfig
     * @param Json $jsonSerializer
     * @param DataLayerComponent $productImpression
     * @param DataLayerComponent $productClick
     * @param array $data
     * @param ConfigInterface|null $config
     * @param UrlBuilder|null $urlBuilder
     */
    public function __construct(
        Context $context,
        HttpContext $httpContext,
        GtmConfig $gtmConfig,
        Json $jsonSerializer,
        DataLayerComponent $productImpression,
        DataLayerComponent $productClick,
        array $data = [],
        ConfigInterface $config = null,
        UrlBuilder $urlBuilder = null
    ) {
        $this->gtmConfig = $gtmConfig;
        $this->jsonSerializer = $jsonSerializer;
        $this->productImpression = $productImpression;
        $this->productClick = $productClick;
        parent::__construct($context, $httpContext, $data, $config, $urlBuilder);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (($this->gtmConfig->isModuleEnabled() && $this->isProductImpressionTrackingEnabled())
            || ($this->gtmConfig->isModuleEnabled() && $this->isProductImpressionTrackingEnabled())) {
                return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isProductImpressionTrackingEnabled()
    {
        return $this->gtmConfig->isModuleEnabled()
            && $this->gtmConfig->getVersion() !== GtmConfig\Source\Version::GA4
            && $this->gtmConfig->isProductImpressionTrackingEnabled();
    }

    /**
     * @return bool
     */
    public function isProductClickTrackingEnabled()
    {
        return $this->gtmConfig->isModuleEnabled() && $this->gtmConfig->isProductClickTrackingEnabled();
    }

    /**
     * @return array
     */
    public function getProductImpressionData()
    {
        return $this->jsonSerializer->serialize(
            $this->productImpression->getData(['object' => $this->getProductItem()])
        );
    }

    public function getProductClickData()
    {
        return $this->jsonSerializer->serialize(
            $this->productClick->getData(['object' => $this->getProductItem()])
        );
    }

    /**
     * Return product for current item
     *
     * @return Product
     */
    public function getProductItem()
    {
        return $this->getItem()->getProduct();
    }
}
