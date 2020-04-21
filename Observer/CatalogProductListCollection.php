<?php

namespace Hatimeria\GtmEe\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer;
use Hatimeria\GtmEe\Model\Config;
use Magento\Framework\App\Request\Http;
use Hatimeria\GtmEe\Model\DataLayerComponent\Search;

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
        if (!$this->config->isModuleEnabled()
            && !$this->config->isSearchTrackingEnabled()
            && $this->request->getFullActionName() != 'catalogsearch_result_index') {
                return $this;
        }

        $collection = $observer->getData('collection');
        $this->searchComponent->processCollection($collection);

        return $this;
    }
}
