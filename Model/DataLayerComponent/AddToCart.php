<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\UA\DataLayerComponent\AddToCart as AddToCartUa;
use Hatimeria\GtmPro\Model\V4\DataLayerComponent\AddToCart as AddToCart4;
use Magento\Quote\Model\Quote\Item;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class AddToCart
 */
class AddToCart extends AbstractComponent
{
    protected AddToCartUa $addToCartUa;
    protected AddToCart4 $addToCart4;

    public function __construct(
        Config      $config,
        AddToCartUa $addToCartUa,
        AddToCart4 $addToCart4
    ) {
        parent::__construct($config);
        $this->addToCartUa = $addToCartUa;
        $this->addToCart4 = $addToCart4;
    }

    /**
     * @param Item $item
     */
    public function processProduct(Item $item)
    {
        if ($this->isGoogleAnalytics4()) {
            $this->addToCart4->processProduct($item);
        } else {
            $this->addToCartUa->processProduct($item);
        }
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        if ($this->isGoogleAnalytics4()) {
            return $this->addToCart4->getComponentData($eventData);
        }
        return $this->addToCartUa->getComponentData($eventData);
    }
}
