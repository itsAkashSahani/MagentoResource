/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
	'jquery', 
	'Magento_Checkout/js/view/form/element/email',
	'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/quote'
], function (
    $,
    Component,
	customer,
	quote
) {
    'use strict';

    return Component.extend({
        defaults: {
            template:
                'Magento_InventoryInStorePickupFrontend/form/element/email',
            links: {
                email:
                    'checkout.steps.shipping-step.shippingAddress.customer-email:email'
            }
        },
		
		quoteHaveNotEmail: function () {
			if(!customer.isLoggedIn() && !quote.guestEmail){
			   return true;
			}
			return false;
        }
    });
});
