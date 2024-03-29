/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/view/billing-address',
	'Magento_Customer/js/model/customer'
], function ($, _, ko, Component, stepNavigator, billingAddress,customer) {
    'use strict';

    var steps = stepNavigator.steps;
	window.onhashchange = () => {
	   if(window.location.hash == '#shipping'){
			$('body').addClass('shipping-step');
			$('body').removeClass('payment-step');
		}else if(window.location.hash == '#payment'){
			$('body').addClass('payment-step');
			$('body').removeClass('shipping-step');
		}
	};
    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/progress-bar',
            visible: true
        },
        steps: steps,

        /** @inheritdoc */
        initialize: function () {
            var stepsValue;

            this._super();
            window.addEventListener('hashchange', _.bind(stepNavigator.handleHash, stepNavigator));
			if(window.location.hash == '#shipping'){
				$('body').addClass('shipping-step');
				$('body').removeClass('payment-step');
			}else if(window.location.hash == '#payment'){
				$('body').addClass('payment-step');
				$('body').removeClass('shipping-step');
			}
            if (!window.location.hash) {
                stepsValue = stepNavigator.steps();
                if (stepsValue.length) {
                    stepNavigator.setHash(stepsValue.sort(stepNavigator.sortItems)[0].code);
                }
            }

            stepNavigator.handleHash();
        },

        /**
         * @param {*} itemOne
         * @param {*} itemTwo
         * @return {*|Number}
         */
        sortItems: function (itemOne, itemTwo) {
            return stepNavigator.sortItems(itemOne, itemTwo);
        },

        /**
         * @param {Object} step
         */
        navigateTo: function (step) {
            if (step.code === 'shipping') {
                billingAddress().needCancelBillingAddressChanges();
            }
            stepNavigator.navigateTo(step.code);
        },

        /**
         * @param {Object} item
         * @return {*|Boolean}
         */
        isProcessed: function (item) {
            return stepNavigator.isProcessed(item.code);
        },
		
		/**
         * @param {Object} item
         * @return {*|Boolean}
         */
        isCustomerLoggedIn: function () {
            return customer.isLoggedIn;
        }
    });
});
