<?php
/**
 * @category   Hatimeria
 * @author      (office@hatimeria.com)
 * @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 * @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

namespace Hatimeria\GtmPro\Model;

use Magento\Quote\Model\QuoteIdMaskFactory;
use Hatimeria\GtmPro\Api\GuestCheckoutStepInterface;
use Hatimeria\GtmPro\Api\CheckoutStepInterface;

/**
 * Class GuestCheckoutStep
 */
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
    public function get($cartId, $step, $param)
    {
        /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        return $this->checkoutStep->get($quoteIdMask->getQuoteId(), $step, $param);
    }
}
