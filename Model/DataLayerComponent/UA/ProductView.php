<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class ProductView
 */
class ProductView extends ComponentAbstract
{
    const EVENT_NAME = 'product-detail-view';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isProductViewTrackingEnabled() && $product = $this->registry->registry('product')) {
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
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }
}
