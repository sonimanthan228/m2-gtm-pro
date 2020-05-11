<?php

namespace Hatimeria\GtmPro\Block\Event;

/**
 * Class Promotion
 */
class Promotion extends AbstractEvent
{
    /**
     * @return bool
     */
    public function isEventEnabled()
    {
        return $this->config->isPromotionTrackingEnabled();
    }
}
