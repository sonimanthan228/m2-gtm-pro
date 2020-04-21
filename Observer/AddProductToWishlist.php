<?php

namespace Hatimeria\GtmEe\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmEe\Model\Config;
use Hatimeria\GtmEe\Model\DataLayerComponent\AddProductToWishlist as AddProductToWishlistComponent;

/**
 * Class AddProductToWishlist
 */
class AddProductToWishlist implements ObserverInterface
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
     * @var AddProductToWishlistComponent
     */
    private $addProductToWishlistComponent;

    /**
     * AddProductToWishlist constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param AddProductToWishlistComponent $addProductToWishlistComponent
     */
    public function __construct(
        Config $config,
        Session $checkoutSession,
        AddProductToWishlistComponent $addProductToWishlistComponent
    ) {
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->addProductToWishlistComponent = $addProductToWishlistComponent;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled() && !$this->config->isAddToWishlistTrackingEnabled()) {
            return $this;
        }

        $product = $observer->getData('product');
        $this->addProductToWishlistComponent->processProduct($product);

        return $this;
    }
}
