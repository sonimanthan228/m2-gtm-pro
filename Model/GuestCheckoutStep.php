<?php

namespace Hatimeria\GtmEe\Model;

use Magento\Quote\Model\QuoteIdMaskFactory;
use Hatimeria\GtmEe\Api\GuestCheckoutStepInterface;
use Hatimeria\GtmEe\Api\CheckoutStepInterface;

class GuestCheckoutStep implements GuestCheckoutStepInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var CheckoutStepInterface
     */
    protected $checkoutStep;

    /**
     * @param \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        QuoteIdMaskFactory  $quoteIdMaskFactory,
        CheckoutStepInterface $checkoutStep
    ) {
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->checkoutStep = $checkoutStep;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId, $step)
    {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->checkoutStep->get($quoteIdMask->getQuoteId(), $step);
    }
}
