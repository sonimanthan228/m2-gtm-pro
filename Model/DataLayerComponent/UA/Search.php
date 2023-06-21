<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class Search
 */
class Search extends ComponentAbstract
{
    const EVENT_NAME = 'site-search';

    /**
     *
     * @param $eventData
     * @return array|mixed
     */
    public function getComponentData($eventData): ?array
    {
        $data = $this->checkoutSession->getGtmProSearchCollectionData();
        if ($data) {
            $data = json_decode($data, true);
            $data['searchTerm'] = $this->request->getParam('q');
            $data['searchResults'] = $data['searchResults'];

            $this->cleanSessionGtmProSearchCollectionData();
        }

        return $data;
    }

    /**
     * @return void
     */
    protected function cleanSessionGtmProSearchCollectionData()
    {
        $this->checkoutSession->setGtmProSearchCollectionData(false);
    }
    /**
     * @param $collection
     */
    public function processProduct($collection)
    {
        if ($data = $this->checkoutSession->getGtmProSearchCollectionData()) {
            $data = json_decode($data, true);
        } else {
            $data = [];
        }

        $data['searchResults'] = $collection->getSize();

        $this->checkoutSession->setGtmProSearchCollectionData(json_encode($data));
    }
}
