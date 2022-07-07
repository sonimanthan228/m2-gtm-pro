<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ProductImpression
 */
class ProductClick extends ComponentAbstract
{
    const EVENT_NAME = 'product-click';

    /**
     * @param $eventData
     * @return array|mixed
     * @throws NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if (array_key_exists('object', $eventData)) {
            $product = $eventData['object'];
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'detail' => [
                   'actionField' => [
                       'list' => $this->getCategoryName($product)
                   ],
                   'products' => $this->getProductStructure($product, false)
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
