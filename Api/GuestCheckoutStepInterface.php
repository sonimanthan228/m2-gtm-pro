<?php

namespace Hatimeria\GtmPro\Api;

/**
 * Interface GuestCheckoutStepInterface
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
