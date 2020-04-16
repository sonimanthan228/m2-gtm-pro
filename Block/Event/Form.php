<?php

namespace Hatimeria\GtmEe\Block\Event;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmEe\Model\Config;

/**
 * Class Form
 * @package Hatimeria\GtmEe\Block\Event
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
