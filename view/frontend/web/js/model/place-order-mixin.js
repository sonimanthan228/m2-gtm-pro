define([
    'jquery',
    'mage/utils/wrapper',
    'Hatimeria_GtmEe/js/model/gtmee-step-manager'
], function ($, wrapper, gtmStepManager) {
    'use strict';

    return function (placeOrderAction) {
        /** Override default place order action and add agreement_ids to request */
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            gtmStepManager.registerStepData();
            return originalAction(paymentData, messageContainer);
        });
    };
});
