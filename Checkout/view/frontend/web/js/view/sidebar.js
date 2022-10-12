define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/sidebar',
	'Magento_Checkout/js/model/step-navigator'
], function (Component, ko, $, sidebarModel,stepNavigator) {
    'use strict';
    return Component.extend({
        /**
         * @param {HTMLElement} element
         */
        setModalElement: function (element) {
            sidebarModel.setPopup($(element));
        },
		showSummary: function () {
			if(stepNavigator.getActiveItemIndex() == 0){
				return true;
			}
			return false;
        }
    });
});
