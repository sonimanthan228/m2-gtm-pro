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
 * Class SalesQuoteRemoveItem
 */
class SalesQuoteRemoveItem implements ObserverInterface
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
     * SalesQuoteRemoveItem constructor.
     * @param Config $config
     * @param DataLayerComponent $removeFromCartComponent
     */
    public function __construct(
        Config $config,
        DataLayerComponent $removeFromCartComponent
    ) {
        $this->config = $config;
        $this->removeFromCartComponent = $removeFromCartComponent;
    }

    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isModuleEnabled() && $this->config->isAddToCartTrackingEnabled()) {
            $this->removeFromCartComponent->processProduct($observer->getQuoteItem());
        }

        return $this;
    }
}
