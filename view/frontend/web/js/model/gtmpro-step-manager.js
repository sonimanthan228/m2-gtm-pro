/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery',
    'mage/url',
    'Magento_Checkout/js/checkout-data',
    'Hatimeria_GtmPro/js/model/gtmpro-url-manager',

    ], function ($, urlBuilder, checkoutData, gtmUrlManager) {
        'use strict';

        const get = function (url, global, contentType, headers) {
        headers = headers || {};
        global = global === undefined ? true : global;
        contentType = contentType || 'application/json';

            return $.ajax({
                url: urlBuilder.build(url),
                type: 'GET',
                global: global,
                contentType: contentType,
                headers: headers,
                async: false
            });
        };

        return {
            registerStepData: function (step) {
                window.dataLayer = window.dataLayer || [];
                if ( window.location.hash) {
                    var step = window.location.hash.replace('#', '');
                    var stepParam = 'none';
                    if (step == 'shipping' && $('input:checked', '.table-checkout-shipping-method').val()) {
                        var stepParam =  $('input:checked', '.table-checkout-shipping-method').val();
                    }
                    if (step == 'payment' && checkoutData.getSelectedPaymentMethod()) {
                        stepParam = checkoutData.getSelectedPaymentMethod();
                    }
                    if (!stepParam) {
                        return this;
                    }
                    get(
                        gtmUrlManager.getUrlForCheckoutStepData(step, stepParam)
                    ).done(
                        function (response) {
                            if (response) {
                                window.dataLayer.push(response[0]);
                            }
                        }
                    );
                }

                return this;
            }
        };
    }
);
