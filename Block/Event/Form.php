<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

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
