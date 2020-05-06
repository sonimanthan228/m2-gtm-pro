<?php

namespace Hatimeria\GtmEe\Block\Event;

/**
 * Class ProductClick
 */
class ProductClick extends AbstractEvent
{
    /**
     * @return bool
     */
    public function isEventEnabled()
    {
        return $this->config->isProductClickTrackingEnabled();
    }

    /**
     * @return array
     */
    public function getProductClickClasses()
    {
        return $this->config->getProductClickLinkClasses();
    }

    /**
     * @return string
     */
    public function getProductClickElementClass()
    {
        return $this->config->getProductClickElementClass();
    }
}
