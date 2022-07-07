<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class ProductImpression
 */
class ProductImpression extends ComponentAbstract
{
    const EVENT_NAME = 'product-impression';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if (array_key_exists('object', $eventData)) {
            $product = $eventData['object'];
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'impressions' => $this->getProductStructure($product, false)
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
