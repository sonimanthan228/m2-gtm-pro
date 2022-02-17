<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
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
            $productStructure = array_merge($this->getProductStructure($product, false), [
                'reviewCount' => $this->getReviewsCount($product),
                'reviewSummary' => $this->getRatingSummary($product)
            ]);
            $data['ecommerce'] = [
                'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'detail' => [
                     'actionField' => [],
                     'products' => [$productStructure]
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
