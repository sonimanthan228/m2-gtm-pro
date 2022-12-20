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
    var productClasses = [];
    var productClickElementClass = '';
    function pushStep() {
        window.dataLayer = window.dataLayer || [];
        $.each(productClasses, function( index, value ) {
            $("." + value).on("click", function (event) {
                fireProductClick(event)
            });
        });
    }

    function fireProductClick(event, element) {
        if (!$(event.currentTarget).hasClass('product-clicked')) {
            event.stopPropagation();
            $(event.currentTarget).addClass('product-clicked');
            var parent = $(event.target).parents('.' + productClickElementClass)[0];
            var data = $(parent).find("*[data-click]").data('click');
            window.dataLayer.push({ ecommerce: null });
            window.dataLayer.push(data);
            $(event.currentTarget).click();
        }
    }

    return function (config) {
        productClasses = config.productClasses;
        productClickElementClass = config.productClickElementClass;
        pushStep();
    };
});
