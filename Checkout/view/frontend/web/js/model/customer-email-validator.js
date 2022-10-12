/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Customer/js/model/customer',
	'Magento_Checkout/js/model/quote',
    'mage/validation'
], function ($, customer,quote) {
    'use strict';

    return {
        /**
         * Validate checkout agreements
         *
         * @returns {Boolean}
         */
        validate: function () {
            var emailValidationResult = customer.isLoggedIn(),
                loginFormSelector = 'form[data-role=email-with-possible-login]';

            if (!customer.isLoggedIn() && !quote.guestEmail) {
                $(loginFormSelector).validation();
                emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
            }
			if (quote.guestEmail) {
                emailValidationResult = quote.guestEmail;
            }

            return emailValidationResult;
        }
    };
});
