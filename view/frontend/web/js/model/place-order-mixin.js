/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery',
    'mage/utils/wrapper',
    'Hatimeria_GtmPro/js/model/gtmpro-step-manager'
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
