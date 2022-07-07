<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class AdvancedSearch
 */
class AdvancedSearch extends ComponentAbstract
{
    const EVENT_NAME = 'advanced-site-search';

    /**
     * @param $eventData
     * @return array
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isSearchTrackingEnabled()
            && $this->request->getFullActionName() == 'catalogsearch_advanced_result') {
               $data['searchTerm'] = $this->getSearchCriterias();
               $data['searchResults'] = $this->catalogSearchAdvanced->getProductCollection()->getSize();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * Get search criteria.
     *
     * @return string
     */
    public function getSearchCriterias(): string
    {
        $result = '';
        $searchCriterias = $this->catalogSearchAdvanced->getSearchCriterias();

        foreach ($searchCriterias as $criteria) {

            if (!empty($result)) {
                $result .= ' | ';
            }
            $result .= $criteria['name'] . ': ' . $criteria['value'];
        }

        return $result;
    }
}
