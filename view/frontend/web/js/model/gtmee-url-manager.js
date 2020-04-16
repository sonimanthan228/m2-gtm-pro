/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
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
            getUrlForCheckoutStepData: function (step) {
                var params = this.getCheckoutMethod() == 'guest' ? //eslint-disable-line eqeqeq
                        {
                            cartId: quote.getQuoteId(),
                            step: step
                        } : {step: step},
                    urls = {
                        'guest': '/guest-carts/:cartId/:step/gtmee-checkout-step-data',
                        'customer': '/carts/mine/:step/gtmee-checkout-step-data'
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
