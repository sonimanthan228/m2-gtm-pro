<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductImpression
 */
class ProductClick extends ComponentAbstract
{
    const EVENT_NAME = 'select_item';

    /**
     * @param $eventData
     * @return array|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if (array_key_exists('object', $eventData)) {
            $product = $eventData['object'];
            $data['ecommerce'] = [
                'items' => $this->getProductStructure($product, true)
            ];
        }

        return $data;
    }
}
