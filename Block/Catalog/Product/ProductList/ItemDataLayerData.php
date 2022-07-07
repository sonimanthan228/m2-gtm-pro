<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Catalog\Product\ProductList;

use Hatimeria\GtmPro\Model\DataLayerComponent;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Hatimeria\GtmPro\Model\Config;
use Magento\Catalog\Block\Product\AwareInterface as ProductAwareInterface;

/**
 * Class ItemDataLayerData
 */
class ItemDataLayerData extends \Magento\Framework\View\Element\Template implements ProductAwareInterface 
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
     * @var DataLayerComponent
     */
    protected $productImpression;

    /**
     * @var DataLayerComponent
     */
    protected $productClick;
    
    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * ItemDataLayerData constructor.
     * @param Context $context
     * @param Config $config
     * @param Json $jsonSerializer
     * @param DataLayerComponent $productImpression
     * @param DataLayerComponent $productClick
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Json $jsonSerializer,
        DataLayerComponent $productImpression,
        DataLayerComponent $productClick,
        array $data = []
    ) {
        $this->config = $config;
        $this->jsonSerializer = $jsonSerializer;
        $this->productImpression = $productImpression;
        $this->productClick = $productClick;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (($this->config->isModuleEnabled() && $this->isProductImpressionTrackingEnabled())
            || ($this->config->isModuleEnabled() && $this->isProductClickTrackingEnabled())) {
                return parent::_toHtml();
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isProductImpressionTrackingEnabled()
    {
        return $this->config->isModuleEnabled()
            && $this->config->getVersion() !== Config\Source\Version::GA4
            && $this->config->isProductImpressionTrackingEnabled();
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
    
    /**
     * {@inheritdoc}
     */
    protected function getCacheLifetime()
    {
        return 86400;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                $this->getProduct() ? $this->getProduct()->getSku() : 'EMPTY'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getCacheTags()
    {
        if ($this->getProduct() !== null) {
            return array_merge(parent::getCacheTags(), [
                'catalog_product_' . $this->getProduct()->getId()
            ]);
        }

        return parent::getCacheTags();
    }
}
