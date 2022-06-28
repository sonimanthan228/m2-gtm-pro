<?php

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Model\Config;
use Psr\Log\LoggerInterface;

abstract class AbstractComponent
{
    protected Config $config;
    protected LoggerInterface $logger;

    public function __construct(
        Config $config,
        LoggerInterface $logger
    )
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function isGoogleAnalytics4()
    {
        return $this->config->getVersion() === Config\Source\Version::GA4;
    }

    /**
     * @param $eventData
     * @return array
     */
    public function getData($eventData)
    {
        try {
            $data = [];
            if ($data = $this->getComponentData($eventData)) {
                $data['event'] = $this->getEventName();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
        }

        return $data;
    }
}