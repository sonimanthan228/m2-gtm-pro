/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

define([
    'jquery'
], function($) {
    "use strict";
    var productClass = '';
    function pushStep() {
        const products = document.querySelectorAll('.' + productClass);
        window.dataLayer = window.dataLayer || [];
        var observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.intersectionRatio > 0 && !$(entry.target).hasClass('intersection-viewed')) {
                    $(entry.target).addClass('intersection-viewed');
                    var impressionData = $(entry.target).find("*[data-impression]").data('impression');
                    if (typeof impressionData !== "undefined" && impressionData.length != 0) {
                        addPositionToProductImpressionData(impressionData, entry);
                        window.dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                        window.dataLayer.push(impressionData);
                    }
                }
            });
        });

        products.forEach(image => {
            observer.observe(image);
        });
    }

    function addPositionToProductImpressionData(impressionData, entry) {
        return impressionData['ecommerce']['impressions']['position'] = $(entry.target).index() + 1;
    }

    return function (config) {
        productClass = config.productClass;
        pushStep();
    };
});
