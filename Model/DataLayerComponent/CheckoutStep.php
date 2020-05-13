<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent;

use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class ProductView
 */
class CheckoutStep extends ComponentAbstract implements DataLayerComponentInterface
{
    const EVENT_NAME = 'checkout-step';

    /**
     * @param $eventData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getComponentData($eventData)
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
                $products[] = [
                   'name' => $this->processName($product->getName()),
                   'id' => $product->getId(),
                   'price' => $this->formatPrice($product->getFinalPrice()),
                   'brand' => $this->getBrand($product),
                   'category' => $this->getCategoryName($product),
                   'variant' => $this->getVariant($item),
                   'quantity' => $item->getQty()
                ];
            }
            $data['ecommerce'] = [
               'currencyCode' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
               'add' => [
                   'actionField' => [
                        'step' => $this->getStep($step),
                        'option' => '' //moved to frontend
                   ],
                   'products' => $products
               ]
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
    protected function getEventName()
    {
        return self::EVENT_NAME;
    }
}
