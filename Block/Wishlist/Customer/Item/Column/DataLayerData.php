<?php

namespace Hatimeria\GtmEe\Block\Wishlist\Customer\Item\Column;

use Magento\Wishlist\Block\Customer\Wishlist\Item\Column;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\View\ConfigInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product;
use Hatimeria\GtmEe\Model\Config as GtmConfig;
use Hatimeria\GtmEe\Model\DataLayerComponent\ProductImpression;
use Hatimeria\GtmEe\Model\DataLayerComponent\ProductClick;

/**
 * Class DataLayerData
 * @package Hatimeria\GtmEe\Block\Wishlist\Customer\Item\Column
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
     * @var ProductImpression
     */
    protected $productImpression;

    /**
     * @var ProductClick
     */
    protected $productClick;

    /**
     * DataLayerData constructor.
     * @param $context
     * @param Context $httpContext
     * @param GtmConfig $gtmConfig
     * @param Json $jsonSerializer
     * @param ProductImpression $productImpression
     * @param ProductClick $productClick
     * @param array $data
     * @param ConfigInterface|null $config
     * @param UrlBuilder|null $urlBuilder
     */
   public function __construct(
        Context $context,
        HttpContext $httpContext,
        GtmConfig $gtmConfig,
        Json $jsonSerializer,
        ProductImpression $productImpression,
        ProductClick $productClick,
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
        return $this->gtmConfig->isModuleEnabled() && $this->gtmConfig->isProductImpressionTrackingEnabled();
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
