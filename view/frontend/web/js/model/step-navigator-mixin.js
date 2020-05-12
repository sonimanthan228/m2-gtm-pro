define([
    'jquery',
    'mage/utils/wrapper',
    'Hatimeria_GtmPro/js/model/gtmpro-step-manager',
    'uiRegistry'
], function ($, wrapper, gtmStepManager) {
    "use strict";

    return function (stepNavigator) {
        stepNavigator.next = wrapper.wrap(stepNavigator.next,
            function (originalAction) {
                gtmStepManager.registerStepData();
                return  originalAction();
            }
        );

        stepNavigator.setHash = wrapper.wrap(stepNavigator.setHash,
            function (originalAction, hash) {
                var result = originalAction(hash);
                gtmStepManager.registerStepData();
                return result;
            }
        );

        return stepNavigator;
    }
});
