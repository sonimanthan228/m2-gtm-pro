<?php

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent\AddProductToCompare as AddProductToCompareComponent;

/**
 * Class AddProductToCompare
 */
class AddProductToCompare implements ObserverInterface
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
     * @var AddProductToCompareComponent
     */
    private $addProductToCompareComponent;

    /**
     * AddProductToCompare constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param AddProductToCompareComponent $addProductToCompareComponent
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        AddProductToCompareComponent $addProductToCompareComponent
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->addProductToCompareComponent = $addProductToCompareComponent;
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
        $this->addProductToCompareComponent->processProduct($product);

        return $this;
    }
}
