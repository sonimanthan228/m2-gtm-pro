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
    function pushStep() {
        const promotions = document.querySelectorAll('[data-promotion-id]');
        window.dataLayer = window.dataLayer || [];
        var observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.intersectionRatio > 0 && !$(entry.target).hasClass('promotion-viewed')) {
                    $(entry.target).addClass('promotion-viewed');
                    var data = getBaseEventData();
                    data.ecommerce.promoView.promotions.push(getPromotionData(entry));
                    window.dataLayer.push({ ecommerce: null });
                    window.dataLayer.push(data);
                }
            });
        });

        promotions.forEach(promotion => {
            observer.observe(promotion);
        });
    }

    function getBaseEventData() {
        var data = {};
        data.ecommerce = {};
        data.ecommerce.promoView = {};
        data.ecommerce.promoView.promotions = [];

        return data;
    }

    function getPromotionData(entry) {
        var data = [];
        data.id = $(entry.target).data('promotion-id');
        data.position = $(entry.target).index() + 1;
        if ($(entry.target).data('promotion-name')) {
            data.name = $(entry.target).data('promotion-name');
        }
        if ($(entry.target).data('promotion-creative')) {
            data.creative = $(entry.target).data('promotion-creative');
        }

        return data;
    }

    return function (config) {
        pushStep();
    };
});
