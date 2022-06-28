<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Model\Config;
use Hatimeria\GtmPro\Model\V4\DataLayerComponent\CategoryView as CategoryView4;

/**
 * Class ProductView
 */
class CategoryView extends AbstractComponent
{
    protected CategoryView4 $categoryView4;

    public function __construct(
        Config $config,
        CategoryView4 $categoryView4
    ) {
        parent::__construct($config);
        $this->categoryView4 = $categoryView4;
    }

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
    {
        if ($this->isGoogleAnalytics4()) {
            return $this->categoryView4->getComponentData($eventData);
        }

        return [];
    }
}
