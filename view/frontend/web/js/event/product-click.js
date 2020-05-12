define([
    'jquery'
], function($) {
    "use strict";
    var productClasses = [];
    var productClickElementClass = '';
    function pushStep() {
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
