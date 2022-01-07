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
 * Class ProductImpression
 */
class ProductClick extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'product-click';

    /**
     * @param $eventData
     * @return array|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
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
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
