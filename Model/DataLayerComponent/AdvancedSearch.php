<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Hatimeria\GtmEe\Api\DataLayerComponentInterface;

/**
 * Class AdvancedSearch
 */
class AdvancedSearch extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'advanced-site-search';

    /**
     * @param $eventData
     * @return array
     */
    public function getComponentData($eventData)
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
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }

    /**
     * Get search criteria.
     *
     * @return array
     */
    public function getSearchCriterias()
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
