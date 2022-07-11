<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model;

use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Hatimeria\GtmPro\Api\CheckoutStepInterface;
use Hatimeria\GtmPro\Model\DataLayerCheckoutService;
use Hatimeria\GtmPro\Api\DataLayerComponentInterface;

/**
 * Class CheckoutStep
 */
class CheckoutStep implements CheckoutStepInterface
{
    /**
     * @var CartTotalRepositoryInterface
     */
    protected $cartTotalRepository;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var DataLayerCheckoutService
     */
    protected $dataLayerCheckoutService;

    /**
     * CheckoutStep constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param DataLayerCheckoutService $dataLayerCheckoutService
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalRepository,
        DataLayerCheckoutService $dataLayerCheckoutService
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->dataLayerCheckoutService = $dataLayerCheckoutService;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId, $step, $param)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->cartRepository->get($cartId);
        $this->validateQuote($quote);

        $eventData = [
            'quote'      => $quote,
            'step'       => $step,
            'step_param' => $param,
        ];

        return $this->dataLayerCheckoutService->getDataLayerComponentData($eventData);
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function validateQuote(\Magento\Quote\Model\Quote $quote)
    {
        if ($quote->getItemsCount() === 0) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Totals calculation is not applicable to empty cart')
            );
        }
    }
}
