<?php

namespace Hatimeria\GtmPro\Model;

use Hatimeria\GtmPro\Api\DataLayerServiceInterface;
use Psr\Log\LoggerInterface;

/**
 * Class DataLayerService
 */
class DataLayerService implements DataLayerServiceInterface
{
    /**
     * @var array|\Hatimeria\GtmPro\Api\DataLayerComponentInterface[]
     */
    private $dataLayerComponent;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DataLayer constructor.
     * @param LoggerInterface $logger
     * @param array $dataLayerComponent
     */
    public function __construct(
        LoggerInterface $logger,
        $dataLayerComponent = []
    ) {
        $this->dataLayerComponent = $dataLayerComponent;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getDataLayerComponentData()
    {
        $dataLayerData = [];
        foreach ($this->dataLayerComponent as $component) {
            if ($data = $component->getData(false)) {
                $dataLayerData[] = $data;
            }
        }

        return $dataLayerData;
    }
}
