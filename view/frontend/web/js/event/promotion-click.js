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
        window.dataLayer = window.dataLayer || [];
        const promotions = document.querySelectorAll('[data-promotion-id]');
        $.each(promotions, function( index, entry ) {
            $(entry).on("click", function (event) {
                firePromotionClick(event)
            });
        });
    }

    function firePromotionClick(event) {
        if (!$(event.currentTarget).hasClass('promotion-clicked')) {
            event.stopPropagation();
            $(event.currentTarget).addClass('promotion-clicked');
            var data = getBaseEventData();
            data.ecommerce.promoClick.promotions.push(getPromotionData(event.currentTarget));
            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push(data);
            $(event.currentTarget).click();
        }
    }

    function getBaseEventData() {
        var data = {};
        data.event = 'promotionClick';
        data.ecommerce = {};
        data.ecommerce.promoClick = {};
        data.ecommerce.promoClick.promotions = [];

        return data;
    }

    function getPromotionData(entry) {
        var data = {};
        data.id = $(entry).data('promotion-id');
        data.position = $(entry).index() + 1;
        if ($(entry).data('promotion-name')) {
            data.name = $(entry).data('promotion-name');
        }
        if ($(entry).data('promotion-creative')) {
            data.creative = $(entry).data('promotion-creative');
        }

        return data;
    }

    return function (config) {
        pushStep();
    };
});
