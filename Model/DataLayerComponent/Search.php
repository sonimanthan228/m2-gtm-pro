<?php

namespace Hatimeria\GtmEe\Model\DataLayerComponent;

use Hatimeria\GtmEe\Api\DataLayerComponentInterface;

/**
 * Class Search
 */
class Search extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'site-search';

    /**
     *
     * @param $eventData
     * @return array|mixed
     */
    public function getComponentData($eventData)
    {
        $data = json_decode($this->checkoutSession->getGtmEeSearchCollectionData(), true);
        if (is_array($data)) {
            $data['searchTerm'] = $this->request->getParam('q');
            $data['searchResults'] = $data['searchResults'];

            $this->cleanSessionGtmEeSearchCollectionData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmEeSearchCollectionData()
    {
        $this->checkoutSession->setGtmEeSearchCollectionData(false);
    }
    /**
     * @param $collection
     */
    public function processCollection($collection)
    {
        $data = json_decode($this->checkoutSession->getGtmEeSearchCollectionData());
        if (!is_array($data)) {
            $data = [];
        }

        $data['searchResults'] = $collection->getSize();

        $this->checkoutSession->setGtmEeSearchCollectionData(json_encode($data));
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
