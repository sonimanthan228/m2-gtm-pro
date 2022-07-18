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
 * Class ReviewAdd
 */
class ReviewAdd implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DataLayerComponent
     */
    private $reviewAddComponent;

    /**
     * ReviewAdd constructor.
     * @param Config $config
     * @param DataLayerComponent $reviewAddComponent
     */
    public function __construct(
        Config $config,
        DataLayerComponent $reviewAddComponent
    ) {
        $this->config = $config;
        $this->reviewAddComponent = $reviewAddComponent;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isModuleEnabled() && $this->config->isAddProductReviewTrackingEnabled()) {
            $data = $observer->getRequest()->getPostValue();
            if ($data) {
                $this->reviewAddComponent->processProduct($data);
            }
        }

        return $this;
    }
}
