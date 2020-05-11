<?php

namespace Hatimeria\GtmPro\Block\Event;

/**
 * Class Form
 */
class Form extends AbstractEvent
{
    /**
     * @return bool
     */
    public function isEventEnabled()
    {
        return $this->config->isFormTrackingEnabled();
    }

    /**
     * @return bool
     */
    public function getFormData()
    {
        return $this->config->getFormTrackingData();
    }
}
