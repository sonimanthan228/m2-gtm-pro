<?php

namespace Hatimeria\GtmEe\Block\Event;

/**
 * Class ProductImpression
 */
class ProductImpression extends AbstractEvent
{
    /**
     * @return bool
     */
    public function isEventEnabled()
    {
        return $this->config->isProductImpressionTrackingEnabled();
    }

    /**
     * @return string
     */
    public function getProductClass()
    {
        return $this->config->getProductImpressionTrackClass();
    }
}
