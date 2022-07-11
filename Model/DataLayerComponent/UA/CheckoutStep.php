<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\UA;

/**
 * Class ProductView
 */
class CheckoutStep extends ComponentAbstract
{
    const EVENT_NAME = 'checkout-step';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->config->isCheckoutStepsTrackingEnabled() &&
            is_array($eventData) && array_key_exists('step', $eventData)) {
            if (is_array($eventData) && array_key_exists('quote', $eventData)) {
                $quote = $eventData['quote'];
            } else {
                $quote = $this->checkoutSession->getQuote();
            }
            $products = [];
            $step = $eventData['step'];
            foreach ($quote->getAllVisibleItems() as $item) {
                $product = $item->getProduct();
                $products[] = array_merge($this->getProductStructure($product, false), [
                   'variant' => $this->getVariant($item),
                   'quantity' => $item->getQty()
                ]);
            }
            $data['ecommerce'] = [
                'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'add'          => [
                    'actionField' => [
                        'step'   => $this->getStep($step),
                        'option' => $eventData['step_param'] !== 'none' ? $eventData['step_param'] : null,
                    ],
                    'products'    => $products,
                ],
            ];
        }

        return $data;
    }

    /**
     * @param $step
     * @return int
     */
    protected function getStep($step)
    {
        switch ($step) {
            case 'shipping':
                $step = 1;
                break;
            case 'payment':
                $step = 2;
                break;
            default:
                $step = 1;
                break;
        }

        return $step;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return self::EVENT_NAME;
    }
}
