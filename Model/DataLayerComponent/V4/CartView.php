<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model\DataLayerComponent\V4;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;

/**
 * Class ProductView
 */
class CartView extends ComponentAbstract
{
    const EVENT_NAME = 'view_cart';

    /**
     * @param $eventData
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getComponentData($eventData): ?array
    {
        $data = [];
        if ($this->request->getFullActionName() === 'checkout_cart_index') {
            $data['ecommerce'] = [
                'currency' => $this->storeManager->getStore()->getCurrentCurrency()->getCode(),
                'value'    => $this->getCartValue(),
                'items'    => $this->getCartItems($this->checkoutSession->getQuote())
            ];
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

    protected function getCartValue(): float
    {
        return $this->checkoutSession->getQuote()->getTotals()['subtotal']->getValueInclTax();
    }
}
