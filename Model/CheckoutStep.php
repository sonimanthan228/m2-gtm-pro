<?php

namespace Hatimeria\GtmPro\Model;

use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Hatimeria\GtmPro\Api\CheckoutStepInterface;
use Hatimeria\GtmPro\Model\DataLayerComponent\CheckoutStep as CheckoutStepComponent;

/**
 * Class TotalsInformationManagement
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
     * @var CheckoutStepComponent
     */
    protected $checkoutStepComponent;

    /**
     * CheckoutStep constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param CartTotalRepositoryInterface $cartTotalRepository
     * @param CheckoutStepComponent $checkoutStepComponent
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalRepository,
        CheckoutStepComponent $checkoutStepComponent
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->checkoutStepComponent = $checkoutStepComponent;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId, $step)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->cartRepository->get($cartId);
        $this->validateQuote($quote);

        $eventData = [
            'quote' => $quote,
            'step' => $step
        ];

        return [$this->checkoutStepComponent->getData($eventData)];
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
