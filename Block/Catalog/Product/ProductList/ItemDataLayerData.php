<?php

namespace Hatimeria\GtmPro\Block\Catalog\Product\ProductList;

use Magento\Catalog\Block\Product\ProductList\Item\Block;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Product;
use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent\ProductImpression;
use Hatimeria\GtmPro\Model\DataLayerComponent\ProductClick;

/**
 * Class ItemDataLayerData
 */
class ItemDataLayerData extends Block
{
    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @var
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
     * ItemDataLayerData constructor.
     * @param Context $context
     * @param Config $config
     * @param Json $jsonSerializer
     * @param ProductImpression $productImpression
     * @param ProductClick $productClick
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $jsonSerializer,
        ProductImpression $productImpression,
        ProductClick $productClick,
        array $data = []
    ) {
        $this->config = $config;
        $this->jsonSerializer = $jsonSerializer;
        $this->productImpression = $productImpression;
        $this->productClick = $productClick;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (($this->config->isModuleEnabled() && $this->isProductImpressionTrackingEnabled())
            || ($this->config->isModuleEnabled() && $this->isProductImpressionTrackingEnabled())) {
                return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isProductImpressionTrackingEnabled()
    {
        return $this->config->isModuleEnabled() && $this->config->isProductImpressionTrackingEnabled();
    }

    /**
     * @return bool
     */
    public function isProductClickTrackingEnabled()
    {
        return $this->config->isModuleEnabled() && $this->config->isProductClickTrackingEnabled();
    }

    /**
     * @return string
     */
    public function getProductImpressionData()
    {
        return $this->jsonSerializer->serialize(
            $this->productImpression->getData(['object' => $this->getProduct()])
        );
    }

    /**
     * @return string
     */
    public function getProductClickData()
    {
        return $this->jsonSerializer->serialize(
            $this->productClick->getData(['object' => $this->getProduct()])
        );
    }
}
