<?php

namespace Hatimeria\GtmEe\Api;

/**
 * Interface GuestCheckoutStepInterface
 * @package Hatimeria\GtmEe\Api
 */
interface GuestCheckoutStepInterface
{
    /**
     * @param string $cartId
     * @param string $step
     * @return array
     */
    public function get($cartId, $step);
}
