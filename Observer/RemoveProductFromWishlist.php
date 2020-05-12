<?php
/**
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Wishlist\Model\ItemFactory;
use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\DataLayerComponent\RemoveProductFromWishlist as RemoveProductFromWishlistComponent;

/**
 * Class RemoveProductFromWishlist
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
     * @param ItemFactory $itemFactory
     * @param Config $config
     * @param Session $checkoutSession
     * @param RemoveProductFromWishlistComponent $removeProductToWishlistComponent
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
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
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
