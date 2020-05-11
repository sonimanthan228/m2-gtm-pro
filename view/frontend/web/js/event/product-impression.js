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
                    if (impressionData) {
                        addPositionToProductImpressionData(impressionData, entry);
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
