<?php

namespace Hatimeria\GtmPro\Api;

/**
 * Interface GuestCheckoutStepDataInterface
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
