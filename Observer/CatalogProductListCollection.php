<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmPro\Model\Config;
use Magento\Framework\App\Request\Http;
use Hatimeria\GtmPro\Model\DataLayerComponent\Search;

/**
 * Class CatalogProductListCollection
 */
class CatalogProductListCollection implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Http
     */
    private $request;

    /**
     * @var Search
     */
    private $searchComponent;

    /**
     * CatalogProductListCollection constructor.
     * @param Config $config
     * @param Http $request
     * @param Search $search
     */
    public function __construct(
        Config $config,
        Http $request,
        Search $search
    ) {
        $this->config = $config;
        $this->request = $request;
        $this->searchComponent = $search;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isModuleEnabled()
            && $this->config->isSearchTrackingEnabled()
            && $this->request->getFullActionName() == 'catalogsearch_result_index'
        ) {
            $this->searchComponent->processCollection($observer->getCollection());
        }

        return $this;
    }
}
