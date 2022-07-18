<?php

namespace Hatimeria\GtmPro\Observer;

use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CartUpdateAfter implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DataLayerComponent
     */
    private $removeFromCartComponent;

    /**
     * @var DataLayerComponent
     */
    private $addToCartComponent;

    /**
     * SalesQuoteRemoveItem constructor.
     * @param Config $config
     * @param DataLayerComponent $removeFromCartComponent
     */
    public function __construct(
        Config $config,
        DataLayerComponent $removeFromCartComponent,
        DataLayerComponent $addToCartComponent
    ) {
        $this->config = $config;
        $this->removeFromCartComponent = $removeFromCartComponent;
        $this->addToCartComponent = $addToCartComponent;
    }

    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled() || !$this->config->isAddToCartTrackingEnabled()) {
            return;
        }

        $info = $observer->getEvent()->getData('info');
        /** @var Cart $cart */
        $cart = $observer->getEvent()->getData('cart');
        foreach ($cart->getQuote()->getAllVisibleItems() as $item) {
            if ((float)$item->getOrigData('qty') !== (float)$item->getQty()) {
                $component = (float)$item->getOrigData('qty') > (float)$item->getQty()
                    ? $this->removeFromCartComponent : $this->addToCartComponent;
                $component->processProduct($item);
            }
        }
    }
}