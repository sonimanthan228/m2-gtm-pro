<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Api;

/**
 * Interface GuestCheckoutStepDataInterface
 */
interface CheckoutStepInterface
{
    /**
     * @param string $cartId
     * @param string $step
     * @param string $param
     * @return mixed
     */
    public function get($cartId, $step, $param);
}
