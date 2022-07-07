<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Observer;

use Hatimeria\GtmPro\Model\DataLayerComponent;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmPro\Model\Config;

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
     * @var DataLayerComponent
     */
    private $addProductToWishlistComponent;

    /**
     * AddProductToWishlist constructor.
     * @param Config $config
     * @param DataLayerComponent $addProductToWishlistComponent
     */
    public function __construct(
        Config $config,
        DataLayerComponent $addProductToWishlistComponent
    ) {
        $this->config = $config;
        $this->addProductToWishlistComponent = $addProductToWishlistComponent;
    }

    /**
     * @param Observer $observerw
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isModuleEnabled() && $this->config->isAddToWishlistTrackingEnabled()) {
            $this->addProductToWishlistComponent->processProduct($observer->getProduct());
        }

        return $this;
    }
}
