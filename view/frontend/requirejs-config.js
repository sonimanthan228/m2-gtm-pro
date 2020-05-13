/*
 *  @category   Hatimeria
 *  @author      (office@hatimeria.com)
 *  @copyright  Copyright (c) 2020 Hatimeria Sp. z o.o. Sp. k. ( https://www.hatimeria.com/ )
 *  @license    (https://www.gnu.org/licenses/gpl-3.0.html)
 */

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
        'data-layer-data': 'Hatimeria_GtmPro/js/data-layer-data',
        'product-impression-event': 'Hatimeria_GtmPro/js/event/product-impression',
        'product-click-event': 'Hatimeria_GtmPro/js/event/product-click',
        'form-event': 'Hatimeria_GtmPro/js/event/form',
        'promotion-impression-event': 'Hatimeria_GtmPro/js/event/promotion-impression',
        'promotion-click-event': 'Hatimeria_GtmPro/js/event/promotion-click'
    }
};
