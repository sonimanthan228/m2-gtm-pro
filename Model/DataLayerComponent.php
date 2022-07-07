<?php

namespace Hatimeria\GtmPro\Model;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

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

    public function isGoogleAnalytics4()
    {
        return $this->config->getVersion() === Config\Source\Version::GA4;
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
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }

        return $data;
    }

    protected function getVersionComponent(): ?DataLayerComponent\AbstractComponent
    {

    }
}