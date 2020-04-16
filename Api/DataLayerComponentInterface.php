<?php

namespace Hatimeria\GtmEe\Api;

/**
 * Interface DataLayerServiceInterface
 * @package Hatimeria\GtmEe\Api
 */
interface DataLayerComponentInterface
{
    /**
     * @return array
     */
    public function getData($eventData);
}
