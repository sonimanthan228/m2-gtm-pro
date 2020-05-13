<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Event;

/**
 * Class ElementClick
 */
class ElementClick extends AbstractEvent
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
