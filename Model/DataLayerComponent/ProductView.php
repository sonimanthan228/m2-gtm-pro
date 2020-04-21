<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Hatimeria\GtmEe\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductView
 */
class ProductView extends ComponentAbstract implements DataLayerComponentInterface
{

    const EVENT_NAME = 'product-detail-view';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        $data = [];
        if ($this->config->isProductViewTrackingEnabled() && $product = $this->coreRegistry->registry('product')) {
            $data['ecommerce'] = [
                'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'detail' => [
                     'actionField' => [],
                     'products' => [
                         'name' => $product->getName(),
                         'id' => $product->getId(),
                         'price' => $this->formatPrice($product->getFinalPrice()),
                         'brand' => $this->getBrand($product),
                         'category' => $this->getCategoryName($product),
                         'reviewCount' => $this->getReviewsCollection($product)->getSize(),
                         'reviewSummary' => $this->getRatingSummary($product)
                     ]
                 ]
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
