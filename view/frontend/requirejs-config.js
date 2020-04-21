var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/step-navigator': {
                'Hatimeria_GtmEe/js/model/step-navigator-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Hatimeria_GtmEe/js/model/place-order-mixin': true
            },
        }
    },
    paths: {
        'product-impression-event': 'Hatimeria_GtmEe/js/event/product-impression',
        'product-click-event': 'Hatimeria_GtmEe/js/event/product-click',
        'form-event': 'Hatimeria_GtmEe/js/event/form',
    },

};
