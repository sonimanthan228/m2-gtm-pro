<?php

namespace Hatimeria\GtmEe\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmEe\Model\Config;
use Hatimeria\GtmEe\Model\DataLayerComponent\RemoveFromCart;

/**
 * Class SalesQuoteRemoveItem
 */
class SalesQuoteRemoveItem implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var RemoveFromCart
     */
    private $removeFromCartComponent;

    /**
     * SalesQuoteRemoveItem constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param RemoveFromCart $removeFromCartComponent
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        RemoveFromCart $removeFromCartComponent
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->removeFromCartComponent = $removeFromCartComponent;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled() && !$this->config->isAddToCartTrackingEnabled()) {
            return $this;
        }

        $item = $observer->getData('quote_item');
        $this->removeFromCartComponent->processProduct($item);

        return $this;
    }
}
