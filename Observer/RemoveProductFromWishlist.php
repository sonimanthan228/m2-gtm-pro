<?php

namespace Hatimeria\GtmEe\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Wishlist\Model\ItemFactory;
use Hatimeria\GtmEe\Model\Config;
use Hatimeria\GtmEe\Model\DataLayerComponent\RemoveProductFromWishlist as RemoveProductFromWishlistComponent;

/**
 * Class RemoveProductFromWishlist
 * @package Hatimeria\GtmEe\Observer
 */
class RemoveProductFromWishlist implements ObserverInterface
{
    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var RemoveProductFromWishlistComponent
     */
    private $removeProductToWishlistComponent;

    /**
     * RemoveProductFromWishlist constructor.
     * @param Config $config
     * @param Session $checkoutSession
     * @param AddProductToWishlistComponent $addProductToWishlistComponent
     */
    public function __construct(
        ItemFactory $itemFactory,
        Config $config,
        Session $checkoutSession,
        RemoveProductFromWishlistComponent $removeProductToWishlistComponent
    ) {
        $this->itemFactory = $itemFactory;
        $this->config = $config;
        $this->checkoutSession = $checkoutSession;
        $this->removeProductToWishlistComponent = $removeProductToWishlistComponent;
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

        $item = $this->itemFactory->create()->load($observer->getRequest()->getParam('item'));
        $this->removeProductToWishlistComponent->processProduct($item->getProduct());

        return $this;
    }
}