<?php

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
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
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var AddToCart
     */
    private $addToCartComponent;

    /**
     * CheckoutCartAddProductComplete constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param AddToCart $addToCartComponent
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        AddToCart $addToCartComponent
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->addToCartComponent = $addToCartComponent;
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
        $this->addToCartComponent->processProduct($item);

        return $this;
    }
}
