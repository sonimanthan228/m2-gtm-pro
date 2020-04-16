<?php

namespace Hatimeria\GtmEe\Api;

/**
 * Interface GuestCheckoutStepDataInterface
 * @package Hatimeria\GtmEe\Api
 */
interface CheckoutStepInterface
{
    /**
     * @param string $cartId
     * @param string $step
     * @return array
     */
    public function get($cartId, $step);
}
