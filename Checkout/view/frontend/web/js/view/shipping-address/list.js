/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
	'jquery',
    'uiComponent',
    'Magento_Customer/js/model/address-list',
    'mage/translate',
    'Magento_Customer/js/model/customer'
], function ($,Component, addressList, $t, customer) {
    'use strict';

    var newAddressOption = {
            getAddressInline: function () {
                return $t('Select a saved address');
            },
            customerAddressId: null
        },
        addressOptions = addressList().filter(function (address) {
            return address.getType() === 'customer-address';
        }),
        addressDefaultIndex = addressOptions.findIndex(function (address) {
            return address.isDefaultShipping();
        });

    return Component.extend({
        defaults: {
            template: 'Albatool_Checkout/shipping-address/list',
            selectedAddress: null,
            isNewAddressSelected: false,
            addressOptions: addressOptions,
            exports: {
                selectedAddress: '${ $.parentName }:selectedAddress'
            }
			
        },

        /**
         * @returns {Object} Chainable.
         */
        initConfig: function () {
            this._super();
            this.addressOptions.unshift(newAddressOption);
            return this;
        },

        /**
         * @return {exports.initObservable}
         */
        initObservable: function () {
            this._super()
                .observe('selectedAddress isNewAddressSelected')
                .observe({
                    isNewAddressSelected: !customer.isLoggedIn() || !addressOptions.length,
                    selectedAddress: this.addressOptions[addressDefaultIndex]
                });

            return this;
        },

        /**
         * @param {Object} address
         * @return {*}
         */
        addressOptionsText: function (address) {
            return address.getAddressInline();
        },

        /**
         * @param {Object} address
         */
        onAddressChange: function (address) {
			if(address.customerAddressId){
                console.log(address);
				if(address.firstname){
					$(document).find('div[name ="shippingAddress.firstname"] input[name="firstname"]').val(address.firstname);
					$(document).find('div[name ="shippingAddress.firstname"] input[name="firstname"]').trigger('keyup');
				}
				if(address.lastname){
					$(document).find('div[name ="shippingAddress.lastname"] input[name="lastname"]').val(address.lastname);
					$(document).find('div[name ="shippingAddress.lastname"] input[name="lastname"]').trigger('keyup');
				}
				if(address.countryId){
					$(document).find("div[name ='shippingAddress.country_id'] select[name='country_id'] option[value='"+address.countryId+"']").attr("selected",true);
					$(document).find("div[name ='shippingAddress.country_id'] select[name='country_id']").trigger('change');
				}
				if(address.city){
					$(document).find('div[name ="shippingAddress.city"] select[name = "city"] option[value="'+address.city+'"]').attr("selected",true);
					$(document).find('div[name ="shippingAddress.city"] select[name = "city"]').trigger('change');
				}
				if(address.postcode){
					$(document).find('div[name ="shippingAddress.postcode"] input[name="postcode"]').val(address.postcode);
					$(document).find('div[name ="shippingAddress.postcode"] input[name="postcode"]').trigger('keyup');
				}
				if(address.telephone){
					$(document).find('div[name ="shippingAddress.telephone"] input[name="telephone"]').val(address.telephone);
					$(document).find('div[name ="shippingAddress.telephone"] input[name="telephone"]').trigger('keyup');
				}
				if(address.street){
					for (var i = 0; i < address.street.length; i++) {
						$(document).find('div[name ="shippingAddress.street.'+i+'"] input[name="street['+i+']"]').val(address.street);
						$(document).find('div[name ="shippingAddress.street.'+i+'"] input[name="street['+i+']"]').trigger('keyup');
					}	
				}
				if(address.fax){
					$(document).find('div[name ="shippingAddress.fax"] input[name="fax"]').val(address.telephone);
					$(document).find('div[name ="shippingAddress.fax"] input[name="fax"]').trigger('keyup');
				}
				if(address.company){
					$(document).find('div[name ="shippingAddress.company"] input[name="company"]').val(address.company);
					$(document).find('div[name ="shippingAddress.company"] input[name="company"]').trigger('keyup');
				}
				if($(document).find('#shipping-save-in-address-book').prop('checked') == true){
					$(document).find('#shipping-save-in-address-book').trigger('click');
				}
			}else{
				if($(document).find('#shipping-save-in-address-book').prop('checked') != true){
					$(document).find('#shipping-save-in-address-book').trigger('click');
				}
			}
        }
    });
});