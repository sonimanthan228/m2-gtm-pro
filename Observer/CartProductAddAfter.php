<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent\AddToCart;

/**
 * Class CartProductAddAfter
 */
class CartProductAddAfter implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var AddToCart
     */
    private $addToCartComponent;

    /**
     * CheckoutCartAddProductComplete constructor.
     * @param Config $config
     * @param AddToCart $addToCartComponent
     */
    public function __construct(
        Config $config,
        AddToCart $addToCartComponent
    ) {
        $this->config = $config;
        $this->addToCartComponent = $addToCartComponent;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isModuleEnabled() && $this->config->isAddToCartTrackingEnabled()) {
            $this->addToCartComponent->processProduct($observer->getQuoteItem());
        }

        return $this;
    }
}
