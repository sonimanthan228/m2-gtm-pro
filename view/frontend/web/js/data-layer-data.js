/*
 *  @category   Hatimeria
 *  @author     (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function($, customerData) {
    "use strict";
    function loadSection() {
        customerData.reload(['gtm'], true).then(pushData());
    }
    function pushData() {
        window.dataLayer = window.dataLayer || [];
        customerData.get('gtm').subscribe(function (gtmData) {
            $.each(gtmData.dataLayer, function( index, data ) {
                window.dataLayer.push({ ecommerce: null });
                window.dataLayer.push(data);
            });
        });
    }

    return function () {
        loadSection();
    };
});
