<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;

/**
 * Class ProductView
 */
class CheckoutStep extends ComponentAbstract
{
    protected ?string $eventName = null;

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isCheckoutStepsTrackingEnabled()
            && (
                $this->request->getFullActionName() === 'checkout_index_index'
                || (is_array($eventData) && array_key_exists('step', $eventData))
            )
        ) {
            $quote = $eventData['quote'] ?? $this->checkoutSession->getQuote();
            $data['ecommerce'] = [
                'currency' => $quote->getQuoteCurrencyCode(),
                'value'    => $this->formatPrice($this->getCartValue($quote)),
                'items'    => $this->getCartItems($quote),
            ];
            switch ($this->getStep($eventData)) {
                case 'shipping':
                    $this->eventName = 'add_shipping_info';
                    $data['ecommerce']['shipping_tier'] = $eventData['step_param'] !== 'none' ? $eventData['step_param'] : null;
                    break;
                case 'payment':
                    $this->eventName = 'add_payment_info';
                    $data['ecommerce']['payment_type'] = $eventData['step_param'] !== 'none' ? $eventData['step_param'] : null;
                    break;
                default:
                    $this->eventName = 'begin_checkout';
                    break;
            }
        }

        return $data;
    }

    /**
     * @throws LocalizedException
     */
    protected function getCartItems(Quote $quote): array
    {
        $data = [];
        foreach($quote->getAllVisibleItems() as $item) {
            $data[] = $this->getCartItemStructure($item);
        }

        return $data;
    }

    /**
     * @param $step
     * @return int
     */
    protected function getStep($eventData)
    {
        return is_array($eventData) && isset($eventData['step']) ? $eventData['step'] : 'start';
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return (string)$this->eventName;
    }

    protected function getCartValue($quote): float
    {
        return $quote->getTotals()['subtotal']->getValueInclTax();
    }
}
