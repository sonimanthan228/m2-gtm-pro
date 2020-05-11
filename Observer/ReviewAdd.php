<?php

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Session\Generic;
use Hatimeria\GtmPro\Model\DataLayerComponent\ReviewAdd as ReviewAddComponent;
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
     * @var Generic
     */
    private $session;

    /**
     * @var ReviewAddComponent
     */
    private $reviewAddComponent;

    /**
     * ReviewAdd constructor.
     * @param Config $config
     * @param Generic $session
     * @param ReviewAddComponent $reviewAddComponent
     */
    public function __construct(
        Config $config,
        Generic $session,
        ReviewAddComponent $reviewAddComponent
    ) {
        $this->config = $config;
        $this->session = $session;
        $this->reviewAddComponent = $reviewAddComponent;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isModuleEnabled() && !$this->config->isAddProductReviewTrackingEnabled()) {
            return $this;
        }

        $request = $observer->getData('request');
        $data = $request->getPostValue();
        if ($data) {
            $this->reviewAddComponent->processReview($data);
        }

        return $this;
    }
}
