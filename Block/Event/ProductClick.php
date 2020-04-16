<?php

namespace Hatimeria\GtmEe\Block\Event;

use Magento\Framework\View\Element\Template;
use Hatimeria\GtmEe\Model\Config;

/**
 * Class Tag
 * @package Hatimeria\GtmEe\Block\Event
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
