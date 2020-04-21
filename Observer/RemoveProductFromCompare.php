<?php

namespace Hatimeria\GtmEe\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmEe\Model\Config;
use Hatimeria\GtmEe\Model\DataLayerComponent\RemoveProductFromCompare as RemoveProductFromCompareComponent;

/**
 * Class RemoveProductFromCompare
 */
class RemoveProductFromCompare implements ObserverInterface
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
     * @var RemoveProductFromCompareComponent
     */
    private $removeProductFromCompareComponent;

    /**
     * RemoveProductFromCompare constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param RemoveProductFromCompareComponent $removeProductFromCompareComponent
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        RemoveProductFromCompareComponent $removeProductFromCompareComponent
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->removeProductFromCompareComponent = $removeProductFromCompareComponent;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled() && !$this->config->isAddToCompareTrackingEnabled()) {
            return $this;
        }

        $product = $observer->getData('product');
        $this->removeProductFromCompareComponent->processData(['product_id' => $product->getProductId()]);

        return $this;
    }
}
