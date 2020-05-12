/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery',
    'mage/storage',
    'Magento_Checkout/js/checkout-data',
    'Hatimeria_GtmPro/js/model/gtmpro-url-manager',

    ], function ($, storage, checkoutData, gtmUrlManager) {
        'use strict';

        return {
            registerStepData: function () {
                if ( window.location.hash) {

                    var step = window.location.hash.replace('#', '');
                    storage.get(
                        gtmUrlManager.getUrlForCheckoutStepData(step)
                    ).done(
                        function (response) {
                            if (response) {
                                if (step == 'shipping') {
                                    var method =  $('input:checked', '.table-checkout-shipping-method').val();
                                    if (method) {
                                        response[0]['ecommerce']['add']['actionField']['option'] = 'shipping = ' + method;
                                    }
                                }

                                if (step == 'payment' && checkoutData.getSelectedPaymentMethod()) {
                                    response[0]['ecommerce']['add']['actionField']['option'] = 'payment = '
                                        + checkoutData.getSelectedPaymentMethod();
                                }

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
