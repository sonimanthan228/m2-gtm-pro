define([
    'jquery',
    'mage/utils/wrapper',
    'Hatimeria_GtmEe/js/model/gtmee-step-manager',
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

        stepNavigator.handleHash = wrapper.wrap(stepNavigator.handleHash,
            function (originalAction) {
                var result = originalAction();
                gtmStepManager.registerStepData();
                return result;
            }
        );

        return stepNavigator;
    }
});
