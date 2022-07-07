<?php

namespace Hatimeria\GtmPro\Model;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;
use Magento\Quote\Model\Quote\Item;
use Psr\Log\LoggerInterface;

class DataLayerComponent implements DataLayerComponentInterface
{
    private Config $config;
    private LoggerInterface $logger;
    private array $versionComponents;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        $versionComponents = []
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->versionComponents = $versionComponents;
    }

    public function getData($eventData)
    {
        try {
            $data = [];
            $component = $this->getVersionComponent();
            if (!$component) {
                return $data;
            }
            if ($data = $component->getComponentData($eventData)) {
                $data['event'] = $component->getEventName();
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }

        return $data;
    }

    public function processProduct($item)
    {
        try {
            $component = $this->getVersionComponent();
            if ($component && method_exists($component, 'processProduct')) {
                $component->processProduct($item);
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }
    }

    protected function getVersionComponent(): ?DataLayerComponent\AbstractComponent
    {
        return $this->versionComponents[$this->config->getVersion()] ?? null;
    }
}