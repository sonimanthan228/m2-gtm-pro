/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/url-builder',
    'mageUtils',
    'Magento_Checkout/js/model/quote'
    ], function (customer, urlBuilder, utils, quote) {
        'use strict';

        return {
            /**
             * @param {Object} quote
             * @return {*}
             */
            getUrlForCheckoutStepData: function (step, stepParam) {
                var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                        {
                            cartId: quote.getQuoteId(),
                            step: step,
                            param: stepParam
                        } : {step: step, param: stepParam},
                    urls = {
                        'guest': '/guest-carts/:cartId/:step/gtmpro-checkout-step-data/:param',
                        'customer': '/carts/mine/:step/gtmpro-checkout-step-data/:param'
                    };

                return this.getUrl(urls, params);
            },


            /**
             * Get url for service.
             *
             * @param {*} urls
             * @param {*} urlParams
             * @return {String|*}
             */
            getUrl: function (urls, urlParams) {
                var url;

                if (utils.isEmpty(urls)) {
                    return 'Provided service call does not exist.';
                }

                if (!utils.isEmpty(urls['default'])) {
                    url = urls['default'];
                } else {
                    url = urls[this.getCheckoutMethod()];
                }

                return urlBuilder.createUrl(url, urlParams);
            },

            /**
             * @return {String}
             */
            getCheckoutMethod: function () {
                return customer.isLoggedIn() ? 'customer' : 'guest';
            }
        };
    }
);
