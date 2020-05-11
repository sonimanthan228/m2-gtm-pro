define([
    'jquery',
    'mage/storage',
    'Magento_Checkout/js/checkout-data',
    'Hatimeria_GtmPro/js/model/gtmpro-url-manager',

    ], function ($, storage, checkoutData, gtmUrlManager) {
        'use strict';

        return {
            registerStepData: function () {
                var step = window.location.hash
                    ? window.location.hash.replace('#', '') : 'shipping';
                storage.get(
                    gtmUrlManager.getUrlForCheckoutStepData(step)
                ).done(
                    function (response) {
                        if (response) {
                            if (step == 'shipping' && window.checkoutConfig['selectedShippingMethod'] != null) {
                                var method = window.checkoutConfig['selectedShippingMethod']['carrier_code'] +
                                    '_' + window.checkoutConfig['selectedShippingMethod']['method_code'];
                                response[0]['ecommerce']['add']['actionField']['option'] = 'shipping = ' + method;
                            }

                            if (step == 'payment' && checkoutData.getSelectedPaymentMethod()) {
                                response[0]['ecommerce']['add']['actionField']['option'] = 'payment = '
                                    + checkoutData.getSelectedPaymentMethod();
                            }

                            window.dataLayer.push(response[0]);
                        }
                    }
                );

                return this;
            }
        };
    }
);
