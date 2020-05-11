var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/step-navigator': {
                'Hatimeria_GtmPro/js/model/step-navigator-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Hatimeria_GtmPro/js/model/place-order-mixin': true
            },
        }
    },
    paths: {
        'product-impression-event': 'Hatimeria_GtmPro/js/event/product-impression',
        'product-click-event': 'Hatimeria_GtmPro/js/event/product-click',
        'form-event': 'Hatimeria_GtmPro/js/event/form',
        'promotion-impression-event': 'Hatimeria_GtmPro/js/event/promotion-impression',
        'promotion-click-event': 'Hatimeria_GtmPro/js/event/promotion-click'
    }
};
