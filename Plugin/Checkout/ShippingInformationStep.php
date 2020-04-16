<?php

namespace Hatimeria\GtmEe\Plugin\Checkout;

class CheckoutShippingStepOptionPlugin
{

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        DataLayerServiceInterface $dataLayerService,
        TrackingServiceInterface $trackingService,
        TrackingSession $trackingSession
    ) {
        $this->trackingSession = $trackingSession;
        $this->dataLayerService = $dataLayerService;
        $this->quoteRepository = $quoteRepository;
        $this->scopeConfig = $scopeConfig;
        $this->trackingService = $trackingService;
    }

    public function afterSaveAddressInformation($subject, $result, $cartId, $addressInformation)
    {
        if ($this->trackingService->isTrackingEnabled()
            && $this->scopeConfig->getValue(Constants::CONFIG_FEATURES_TRACK_CHECKOUT_STEPS, 'store')) {
            $quote = $this->quoteRepository->getActive($cartId);
            $dataLayer = $this->dataLayerService->convertToDataLayer(
                Constants::TRACKING_TYPE_CHECKOUT_SHIPPING_OPTION,
                false,
                $quote
            );
            $this->trackingSession->setTrackingEvent($dataLayer);
        }
        return $result;
    }
}
