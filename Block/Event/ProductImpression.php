<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Block\Event;

use Hatimeria\GtmPro\Model\Config\Source\Version;

/**
 * Class ProductImpression
 */
class ProductImpression extends AbstractEvent
{
    public function toHtml()
    {
        if ($this->config->getVersion() === Version::GA4) {
            return '';
        }

        return parent::toHtml();
    }

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
